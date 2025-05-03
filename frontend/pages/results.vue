<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center mb-10">
      <h1 class="text-3xl font-bold text-pink-600 mb-2">投票結果</h1>
      <p class="text-gray-600">あなたの嫁ランキング</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">現在のランキング</h2>
        <div class="text-sm text-gray-500">総投票数: {{ totalVotes }}</div>
      </div>
      
      <div class="space-y-6">
        <div 
          v-for="(character, index) in sortedResults" 
          :key="character.id" 
          class="border rounded-lg p-4 flex items-center hover:bg-pink-50 transition-colors"
          :class="{'bg-pink-50 border-pink-300': index === 0, 'border-gray-200': index !== 0}"
        >
          <div 
            class="w-10 h-10 flex items-center justify-center rounded-full mr-4 text-white font-bold"
            :class="getRankClass(index)"
          >
            {{ index + 1 }}
          </div>
          <img 
            :src="character.image_url || '/placeholder.png'" 
            :alt="character.name" 
            class="w-16 h-16 object-cover rounded-lg mr-4 border-2"
            :class="{'border-yellow-400': index === 0, 'border-gray-300': index !== 0}"
          >
          <div class="flex-1">
            <h3 class="font-semibold text-lg">{{ character.name }}</h3>
            <div class="text-sm text-gray-600">{{ character.vote_count }} 票</div>
          </div>
          <div class="w-1/3 bg-gray-200 rounded-full h-6 overflow-hidden">
            <div 
              class="h-full rounded-full"
              :class="getBarClass(index)"
              :style="{ width: calculatePercentage(character.vote_count) + '%' }"
            ></div>
          </div>
          <span class="ml-3 text-sm font-medium w-12 text-right">{{ calculatePercentage(character.vote_count) }}%</span>
        </div>
      </div>
    </div>
    
    <div class="mt-10 text-center space-x-4">
      <nuxt-link to="/" class="bg-pink-500 hover:bg-pink-600 text-white py-3 px-6 rounded-lg shadow-md inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        投票ページに戻る
      </nuxt-link>
      
      <nuxt-link to="/history" class="bg-white border border-pink-500 text-pink-500 hover:bg-pink-50 py-3 px-6 rounded-lg shadow-md inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
        </svg>
        過去の投票結果を見る
      </nuxt-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const results = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    loading.value = true
    const response = await fetch('/api/votes/results')
    results.value = await response.json()
    loading.value = false
  } catch (error) {
    console.error('Error fetching results:', error)
    loading.value = false
  }
})

const sortedResults = computed(() => {
  return [...results.value].sort((a, b) => b.vote_count - a.vote_count)
})

const totalVotes = computed(() => {
  return results.value.reduce((sum, character) => sum + character.vote_count, 0)
})

const calculatePercentage = (votes) => {
  if (totalVotes.value === 0) return 0
  return Math.round((votes / totalVotes.value) * 100)
}

// ランキングに応じた色を取得
const getRankClass = (index) => {
  switch (index) {
    case 0: return 'bg-yellow-500' // 1位
    case 1: return 'bg-gray-400'   // 2位
    case 2: return 'bg-amber-600'  // 3位
    default: return 'bg-pink-500'  // その他
  }
}

// グラフバーの色を取得
const getBarClass = (index) => {
  switch (index) {
    case 0: return 'bg-yellow-500' // 1位
    case 1: return 'bg-gray-400'   // 2位
    case 2: return 'bg-amber-600'  // 3位
    default: return 'bg-pink-500'  // その他
  }
}
</script>
