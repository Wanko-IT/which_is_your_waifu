export const state = () => ({
  characters: [],
  votingResults: []
})

export const mutations = {
  SET_CHARACTERS(state, characters) {
    state.characters = characters
  },
  SET_VOTING_RESULTS(state, results) {
    state.votingResults = results
  }
}

export const actions = {
  async fetchCharacters({ commit }) {
    try {
      const characters = await this.$axios.$get('/characters')
      commit('SET_CHARACTERS', characters)
      return characters
    } catch (error) {
      console.error('Error fetching characters:', error)
      throw error
    }
  },
  
  async fetchResults({ commit }) {
    try {
      const results = await this.$axios.$get('/votes/results')
      commit('SET_VOTING_RESULTS', results)
      return results
    } catch (error) {
      console.error('Error fetching voting results:', error)
      throw error
    }
  },
  
  async voteForCharacter({ dispatch }, characterId) {
    try {
      await this.$axios.$post('/votes', { character_id: characterId })
      await dispatch('fetchResults')
    } catch (error) {
      console.error('Error voting for character:', error)
      throw error
    }
  }
}

export const getters = {
  getCharacters: state => state.characters,
  getVotingResults: state => state.votingResults,
  getSortedResults: state => {
    return [...state.votingResults].sort((a, b) => b.vote_count - a.vote_count)
  },
  getTotalVotes: state => {
    return state.votingResults.reduce((sum, character) => sum + character.vote_count, 0)
  }
}
