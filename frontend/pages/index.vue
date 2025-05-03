<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Which Is Your Waifu?</h1>
    <p class="text-gray-600 mb-8">Vote for your favorite character!</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="character in characters" :key="character.id" class="border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
        <img :src="character.image_url || '/placeholder.png'" :alt="character.name" class="w-full h-48 object-cover rounded mb-4">
        <h3 class="text-lg font-semibold mb-2">{{ character.name }}</h3>
        <p v-if="character.description" class="text-sm text-gray-600 mb-4">{{ character.description }}</p>
        <button @click="voteForCharacter(character.id)" class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded w-full">
          Vote for {{ character.name }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const characters = ref([])

onMounted(async () => {
  try {
    const response = await fetch('/api/characters')
    characters.value = await response.json()
  } catch (error) {
    console.error('Error fetching characters:', error)
  }
})

const voteForCharacter = async (characterId) => {
  try {
    await fetch('/api/votes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ character_id: characterId })
    })
    router.push('/results')
  } catch (error) {
    console.error('Error voting:', error)
  }
}
</script>
