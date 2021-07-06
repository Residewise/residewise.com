import store from './main'
import axios from 'axios'

export default {
  namespaced: true,
  state:{
    properties: [],
  },
  getters:{
    getProperties(state){
      return state.properties
    }
  },
  mutations:{
    setProperties(state, properties){
      state.properties = properties
    }
  },
  actions:{
   async get({commit}, query ){
    const url = new URL("http://localhost:8000/api/properties.json?fee[between]="+query.min+'..'+query.max);

   let res =  await axios.get(url.href, { 
      params: {
        type: query.type,
        contract: query.contract 
      }
    }).then((response)=>{
      commit('setProperties', response.data)
    })
    

      
    }

  }
}