<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold">Which Is Your Waifu?</h1>
        <p class="text-gray-600">あなたの嫁を選んでください！</p>
      </div>
      <div>
        <nuxt-link to="/login" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-md text-sm">
          ログイン
        </nuxt-link>
      </div>
    </div>
    
    <div v-if="votingPeriod" class="mb-6 p-4 bg-pink-50 rounded-lg border border-pink-200">
      <h2 class="text-lg font-semibold text-pink-800 mb-2">投票期間</h2>
      <p class="text-sm text-pink-700">
        {{ formatDate(votingPeriod.start_date) }} から {{ formatDate(votingPeriod.end_date) }} まで
      </p>
    </div>
    
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
        <div v-if="currentMatchup[0].description" class="text-sm text-gray-600 mb-4 text-center">{{ currentMatchup[0].description }}</div>
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
        <div v-if="currentMatchup[1].description" class="text-sm text-gray-600 mb-4 text-center">{{ currentMatchup[1].description }}</div>
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
const votingPeriod = ref(null)
const isAuthenticated = ref(false)
const redirectPath = ref('')

// 日付をフォーマットする関数
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
}

// ユーザー認証状態を確認
const checkAuth = async () => {
  try {
    const response = await fetch('/api/user', {
      credentials: 'include'
    })
    
    if (response.ok) {
      isAuthenticated.value = true
      // ログイン前のページにリダイレクト
      if (redirectPath.value) {
        router.push(redirectPath.value)
        redirectPath.value = ''
      }
    } else {
      isAuthenticated.value = false
    }
  } catch (error) {
    console.error('Error checking auth:', error)
    isAuthenticated.value = false
  }
}

// キャラクターデータと投票期間を取得
onMounted(async () => {
  try {
    loading.value = true
    
    // 認証状態を確認
    await checkAuth()
    
    // キャラクターデータを取得
    const charactersResponse = await fetch('/api/characters')
    characters.value = await charactersResponse.json()
    
    // 投票期間を取得
    const periodResponse = await fetch('/api/voting-period')
    if (periodResponse.ok) {
      votingPeriod.value = await periodResponse.json()
    }
    
    // 最初のマッチアップを設定
    if (characters.value.length >= 2) {
      setupNextMatchup()
    } else {
      currentMatchup.value = []
    }
    
    loading.value = false
  } catch (error) {
    console.error('Error fetching data:', error)
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
    // 認証チェック
    if (!isAuthenticated.value) {
      // 現在のパスを保存してログインページにリダイレクト
      redirectPath.value = router.currentRoute.value.fullPath
      router.push('/login')
      return
    }
    
    // 投票を記録
    await fetch('/api/votes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      credentials: 'include',
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
