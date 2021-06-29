import Vue from 'vue'
import App from './views/App'
import router from './router/main'
import store from './store/main'
import axios from 'axios'

Vue.config.productionTip = false

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.put['Content-Type'] = 'application/json';
axios.defaults.headers.get['Content-Type'] = 'application/json';
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.delete['Content-Type'] = 'application/json';

  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#root')