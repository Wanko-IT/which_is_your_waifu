<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">投票履歴</h1>
    
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-pink-500"></div>
    </div>
    
    <div v-else-if="votingHistory.length === 0" class="text-center py-12">
      <p class="text-lg text-gray-700 mb-4">投票履歴がありません</p>
      <nuxt-link to="/" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-6 rounded">
        投票ページへ戻る
      </nuxt-link>
    </div>
    
    <div v-else>
      <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">過去の投票期間</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div 
            v-for="period in votingPeriods" 
            :key="period.id" 
            class="border rounded-lg p-4 cursor-pointer hover:bg-pink-50"
            :class="{ 'bg-pink-50 border-pink-300': selectedPeriod === period.id }"
            @click="selectPeriod(period.id)"
          >
            <h3 class="font-medium">{{ period.name || '無題の投票' }}</h3>
            <p class="text-sm text-gray-600">
              {{ formatDate(period.start_date) }} から {{ formatDate(period.end_date) }} まで
            </p>
            <div class="mt-2 text-sm text-pink-600">
              {{ period.total_votes }} 票
            </div>
          </div>
        </div>
      </div>
      
      <div v-if="selectedPeriod">
        <h2 class="text-xl font-semibold mb-4">投票結果</h2>
        <div class="space-y-4">
          <div v-for="(character, index) in sortedResults" :key="character.id" class="border rounded-lg p-4 flex items-center">
            <div class="bg-pink-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4">
              {{ index + 1 }}
            </div>
            <img :src="character.image_url || '/placeholder.png'" :alt="character.name" class="w-12 h-12 object-cover rounded-full mr-4">
            <div class="flex-1">
              <h3 class="font-semibold">{{ character.name }}</h3>
              <div class="text-sm text-gray-600">{{ character.vote_count }} 票</div>
            </div>
            <div class="w-1/2 bg-gray-200 rounded-full h-4 overflow-hidden">
              <div class="bg-pink-500 h-full" :style="{ width: calculatePercentage(character.vote_count) + '%' }"></div>
            </div>
            <span class="ml-2 text-sm font-medium">{{ calculatePercentage(character.vote_count) }}%</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-8 text-center">
      <nuxt-link to="/" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-6 rounded">
        投票ページへ戻る
      </nuxt-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(true)
const votingHistory = ref([])
const votingPeriods = ref([])
const selectedPeriod = ref(null)
const periodResults = ref([])

// 日付をフォーマットする関数
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
}

// 投票履歴を取得
onMounted(async () => {
  try {
    loading.value = true
    
    // 投票期間の一覧を取得
    const periodsResponse = await fetch('/api/voting-periods', {
      credentials: 'include'
    })
    
    if (periodsResponse.ok) {
      votingPeriods.value = await periodsResponse.json()
      
      if (votingPeriods.value.length > 0) {
        // 最新の投票期間を選択
        selectPeriod(votingPeriods.value[0].id)
      }
    }
    
    loading.value = false
  } catch (error) {
    console.error('Error fetching voting history:', error)
    loading.value = false
  }
})

// 投票期間を選択
const selectPeriod = async (periodId) => {
  try {
    selectedPeriod.value = periodId
    loading.value = true
    
    // 選択した期間の結果を取得
    const resultsResponse = await fetch(`/api/voting-periods/${periodId}/results`, {
      credentials: 'include'
    })
    
    if (resultsResponse.ok) {
      periodResults.value = await resultsResponse.json()
    }
    
    loading.value = false
  } catch (error) {
    console.error('Error fetching period results:', error)
    loading.value = false
  }
}

// 結果を得票数順にソート
const sortedResults = computed(() => {
  return [...periodResults.value].sort((a, b) => b.vote_count - a.vote_count)
})

// 総投票数を計算
const totalVotes = computed(() => {
  return periodResults.value.reduce((sum, character) => sum + character.vote_count, 0)
})

// パーセンテージを計算
const calculatePercentage = (votes) => {
  if (totalVotes.value === 0) return 0
  return Math.round((votes / totalVotes.value) * 100)
}
</script>
