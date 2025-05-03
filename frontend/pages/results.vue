<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Voting Results</h1>
    
    <div class="space-y-4">
      <div v-for="(character, index) in sortedResults" :key="character.id" class="border rounded-lg p-4 flex items-center">
        <div class="bg-pink-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4">
          {{ index + 1 }}
        </div>
        <img :src="character.image_url || '/placeholder.png'" :alt="character.name" class="w-12 h-12 object-cover rounded-full mr-4">
        <div class="flex-1">
          <h3 class="font-semibold">{{ character.name }}</h3>
          <div class="text-sm text-gray-600">{{ character.vote_count }} votes</div>
        </div>
        <div class="w-1/2 bg-gray-200 rounded-full h-4 overflow-hidden">
          <div class="bg-pink-500 h-full" :style="{ width: calculatePercentage(character.vote_count) + '%' }"></div>
        </div>
        <span class="ml-2 text-sm font-medium">{{ calculatePercentage(character.vote_count) }}%</span>
      </div>
    </div>
    
    <div class="mt-8 text-center">
      <button @click="$router.push('/')" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-6 rounded">
        Back to Voting
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const results = ref([])

onMounted(async () => {
  try {
    const response = await fetch('/api/votes/results')
    results.value = await response.json()
  } catch (error) {
    console.error('Error fetching results:', error)
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
</script>
