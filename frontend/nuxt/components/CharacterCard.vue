<template>
  <div class="character-card">
    <img :src="character.image_url || '/placeholder.png'" :alt="character.name" class="character-image">
    <h3 class="character-name">{{ character.name }}</h3>
    <p v-if="character.description" class="character-description">{{ character.description }}</p>
    <button @click="vote" class="vote-button">
      Vote for {{ character.name }}
    </button>
  </div>
</template>

<script>
export default {
  props: {
    character: {
      type: Object,
      required: true
    }
  },
  methods: {
    vote() {
      this.$emit('vote', this.character.id)
    }
  }
}
</script>

<style scoped>
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
