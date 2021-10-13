import Vue from 'vue'
import App from './views/App'
import router from './router/main'
import store from './store/main'
import axios from 'axios'
import VueI18n from 'vue-i18n'
import i18n from './il8n'

import './app.scss'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(require('vue-moment'));

Vue.config.productionTip = false
axios.defaults.baseURL = 'https://127.0.0.1:8000'

axios.defaults.headers.common.Accept = 'application/json'
axios.defaults.headers.put['Content-Type'] = 'application/json'
axios.defaults.headers.get['Content-Type'] = 'application/json'
axios.defaults.headers.post['Content-Type'] = 'application/json'
axios.defaults.headers.delete['Content-Type'] = 'application/json'

Vue.use(VueI18n)

require('./store/subscriber')
store.dispatch('auth/attempt', localStorage.getItem('token')).then(() => {
  new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
  }).$mount('#root')
})
