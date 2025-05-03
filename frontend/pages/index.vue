<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Which Is Your Waifu?</h1>
    <p class="text-gray-600 mb-8">あなたの嫁を選んでください！</p>
    
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-pink-500"></div>
    </div>
    
    <div v-else-if="currentMatchup.length === 0" class="text-center py-12">
      <p class="text-lg text-gray-700 mb-4">投票が終了しました！</p>
      <button @click="$router.push('/results')" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-6 rounded">
        結果を見る
      </button>
    </div>
    
    <div v-else class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-8">
      <!-- 左側のキャラクター -->
      <div class="w-full md:w-2/5 border rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow bg-white">
        <img :src="currentMatchup[0].image_url || '/placeholder.png'" :alt="currentMatchup[0].name" class="w-full h-64 object-cover rounded mb-4">
        <h3 class="text-xl font-bold mb-2 text-center">{{ currentMatchup[0].name }}</h3>
        <p v-if="currentMatchup[0].description" class="text-sm text-gray-600 mb-4 text-center">{{ currentMatchup[0].description }}</p>
        <button @click="voteForCharacter(currentMatchup[0].id)" class="bg-pink-500 hover:bg-pink-600 text-white py-3 px-4 rounded w-full text-lg font-semibold">
          投票する
        </button>
      </div>
      
      <!-- VS -->
      <div class="flex flex-col items-center justify-center">
        <div class="bg-pink-600 text-white text-3xl font-bold py-2 px-4 rounded-full shadow-md">
          VS
        </div>
      </div>
      
      <!-- 右側のキャラクター -->
      <div class="w-full md:w-2/5 border rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow bg-white">
        <img :src="currentMatchup[1].image_url || '/placeholder.png'" :alt="currentMatchup[1].name" class="w-full h-64 object-cover rounded mb-4">
        <h3 class="text-xl font-bold mb-2 text-center">{{ currentMatchup[1].name }}</h3>
        <p v-if="currentMatchup[1].description" class="text-sm text-gray-600 mb-4 text-center">{{ currentMatchup[1].description }}</p>
        <button @click="voteForCharacter(currentMatchup[1].id)" class="bg-pink-500 hover:bg-pink-600 text-white py-3 px-4 rounded w-full text-lg font-semibold">
          投票する
        </button>
      </div>
    </div>
    
    <div class="mt-12 text-center">
      <button v-if="currentMatchup.length > 0" @click="skipMatchup" class="text-gray-500 hover:text-gray-700 underline">
        スキップして次へ
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const characters = ref([])
const currentMatchup = ref([])
const loading = ref(true)
const votedPairs = ref(new Set())

// キャラクターデータを取得
onMounted(async () => {
  try {
    loading.value = true
    const response = await fetch('/api/characters')
    characters.value = await response.json()
    
    // 最初のマッチアップを設定
    if (characters.value.length >= 2) {
      setupNextMatchup()
    } else {
      currentMatchup.value = []
    }
    
    loading.value = false
  } catch (error) {
    console.error('Error fetching characters:', error)
    loading.value = false
  }
})

// 次のマッチアップを設定
const setupNextMatchup = () => {
  // まだ投票していないペアを見つける
  const availablePairs = findAvailablePairs()
  
  if (availablePairs.length > 0) {
    // ランダムにペアを選択
    const randomIndex = Math.floor(Math.random() * availablePairs.length)
    const [char1, char2] = availablePairs[randomIndex]
    currentMatchup.value = [char1, char2]
  } else {
    // 全てのペアに投票済み
    currentMatchup.value = []
  }
}

// 投票していないペアを見つける
const findAvailablePairs = () => {
  const pairs = []
  
  for (let i = 0; i < characters.value.length; i++) {
    for (let j = i + 1; j < characters.value.length; j++) {
      const pairKey = `${characters.value[i].id}-${characters.value[j].id}`
      const reversePairKey = `${characters.value[j].id}-${characters.value[i].id}`
      
      if (!votedPairs.value.has(pairKey) && !votedPairs.value.has(reversePairKey)) {
        pairs.push([characters.value[i], characters.value[j]])
      }
    }
  }
  
  return pairs
}

// キャラクターに投票
const voteForCharacter = async (characterId) => {
  try {
    // 投票を記録
    await fetch('/api/votes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ character_id: characterId })
    })
    
    // 投票したペアを記録
    if (currentMatchup.value.length === 2) {
      const pairKey = `${currentMatchup.value[0].id}-${currentMatchup.value[1].id}`
      votedPairs.value.add(pairKey)
    }
    
    // 次のマッチアップを設定
    setupNextMatchup()
    
    // 全てのペアに投票済みの場合は結果ページへ
    if (currentMatchup.value.length === 0) {
      router.push('/results')
    }
  } catch (error) {
    console.error('Error voting:', error)
  }
}

// マッチアップをスキップ
const skipMatchup = () => {
  setupNextMatchup()
}
</script>
