<template>
  <div class="container">
    <h1 class="title">Which Is Your Waifu?</h1>
    <p class="subtitle">Vote for your favorite character!</p>
    
    <div class="characters-grid">
      <CharacterCard 
        v-for="character in characters" 
        :key="character.id" 
        :character="character"
        @vote="voteForCharacter"
      />
    </div>
  </div>
</template>

<script>
import CharacterCard from '~/components/CharacterCard.vue'

export default {
  components: {
    CharacterCard
  },
  data() {
    return {
      characters: []
    }
  },
  async fetch() {
    try {
      this.characters = await this.$axios.$get('/characters')
    } catch (error) {
      console.error('Error fetching characters:', error)
    }
  },
  methods: {
    async voteForCharacter(characterId) {
      try {
        await this.$axios.$post('/votes', { character_id: characterId })
        this.$router.push('/results')
      } catch (error) {
        console.error('Error voting:', error)
      }
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  text-align: center;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
}

.subtitle {
  font-size: 1.2rem;
  color: #666;
  margin-bottom: 2rem;
}

.characters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.character-card {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 1.5rem;
  transition: transform 0.3s ease;
}

.character-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.character-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.character-name {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.character-description {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 1rem;
}

.vote-button {
  background-color: #ff6b6b;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.vote-button:hover {
  background-color: #ff5252;
}
</style>
