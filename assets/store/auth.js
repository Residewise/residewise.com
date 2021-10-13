import axios from 'axios'

export default {
  namespaced: true,
  state: {
    token: null,
    user: null,
  },
  getters: {
    isAuthenticated (state) {
      return state.token && state.user
    },
    getUser (state) {
      return state.user
    },
    getToken (state) {
      return state.token
    }
  },
  mutations: {
    setToken (state, token) {
      state.token = token
    },
    setUser (state, user) {
      state.user = user
    },
    setDecodedToken (state, decode) {
      state.decodedToken = decode
    }
  },
  actions: {
    async login ({ dispatch }, credentails) {
      const response = await axios.post('/api/login', credentails)
      return dispatch('attempt', response.data.token)
    },
    async attempt ({ commit, state }, token) {
      if (token) {
        commit('setToken', token)
      }

      if (!state.token)
      {
        return
      }

      try {
        const response = await axios.get('/api/me')
        commit('setUser', response.data)
      } catch (e) {
        commit('setUser', null)
        commit('setToken', null)
      }
    },
    async register (_, form) {
      return await axios.post('/api/users', form)
    },
    logout ({ commit }) {
      localStorage.removeItem('token')
      commit('setUser', null)
      commit('setToken', null)
    }
  }
}
