<template>
  <div class="container">
    <h1 class="title">Voting Results</h1>
    
    <div class="results-container">
      <div v-for="(character, index) in sortedResults" :key="character.id" class="result-card">
        <div class="rank">{{ index + 1 }}</div>
        <img :src="character.image_url || '/placeholder.png'" :alt="character.name" class="character-image">
        <div class="character-info">
          <h3 class="character-name">{{ character.name }}</h3>
          <div class="votes-count">{{ character.vote_count }} votes</div>
        </div>
        <div class="progress-container">
          <div class="progress-bar" :style="{ width: calculatePercentage(character.vote_count) + '%' }"></div>
          <span class="percentage">{{ calculatePercentage(character.vote_count) }}%</span>
        </div>
      </div>
    </div>
    
    <div class="actions">
      <button @click="$router.push('/')" class="back-button">Back to Voting</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      results: []
    }
  },
  async fetch() {
    try {
      this.results = await this.$axios.$get('/votes/results')
    } catch (error) {
      console.error('Error fetching results:', error)
    }
  },
  computed: {
    sortedResults() {
      return [...this.results].sort((a, b) => b.vote_count - a.vote_count)
    },
    totalVotes() {
      return this.results.reduce((sum, character) => sum + character.vote_count, 0)
    }
  },
  methods: {
    calculatePercentage(votes) {
      if (this.totalVotes === 0) return 0
      return Math.round((votes / this.totalVotes) * 100)
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.title {
  font-size: 2.5rem;
  text-align: center;
  margin-bottom: 2rem;
}

.results-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.result-card {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.rank {
  font-size: 1.5rem;
  font-weight: bold;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #ff6b6b;
  color: white;
  border-radius: 50%;
  margin-right: 1rem;
}

.character-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 50%;
  margin-right: 1rem;
}

.character-info {
  flex: 1;
}

.character-name {
  font-size: 1.2rem;
  margin: 0 0 0.25rem;
}

.votes-count {
  font-size: 0.9rem;
  color: #666;
}

.progress-container {
  flex: 2;
  height: 24px;
  background-color: #eee;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  margin-left: 1rem;
}

.progress-bar {
  height: 100%;
  background-color: #ff6b6b;
  transition: width 0.5s ease;
}

.percentage {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.9rem;
  font-weight: bold;
}

.actions {
  margin-top: 2rem;
  text-align: center;
}

.back-button {
  background-color: #ff6b6b;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.back-button:hover {
  background-color: #ff5252;
}
</style>
