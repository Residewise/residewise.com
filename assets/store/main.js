import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios' 

axios.defaults.baseURL = 'http://127.0.0.1:8000/'
Vue.use(Vuex)

export default new Vuex.Store({
  state:{
    isLoading: false
  },
  getters:{
    getLoading(state){
      return state.isLoading
    }
  },
  mutations:{
    setLoading(state, newLoadingState){
      state.isLoading = newLoadingState
    }
  },
  actions:{
  },
  modules:{
  }
})