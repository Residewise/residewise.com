import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios' 
import property from './property'

axios.defaults.baseURL = 'http://127.0.0.1:8000/'
Vue.use(Vuex)

export default new Vuex.Store({
  state:{
    isLoading: false,
    language: null
  },
  getters:{
    getLoading(state){
      return state.isLoading
    },
    getLanguage(state){
      return state.language
    }
  },
  mutations:{
    setLoading(state, loading){
      state.isLoading = loading
    },
    setLanguage(state, language){
      state.language = language
    }
  },
  actions:{
  },
  modules:{
    property
  }
})