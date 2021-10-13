import axios from 'axios'

export default {
  namespaced: true,
  state: {
    properties: []
  },
  getters: {
    getProperties (state) {
      return state.properties
    }
  },
  mutations: {
    setProperties (state, properties) {
      state.properties = properties
    }
  },
  actions: {
    async get ({ commit }, params) {
      await axios.get('/api/properties.json', {
        params: {
          type: params.type,
          fee: '[between]=' + params.min + '...' + params.max
        }
      }).then((response) => {
        commit('setProperties', response.data)
      })
    }

  }
}