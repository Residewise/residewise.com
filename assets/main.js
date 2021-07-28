import Vue from 'vue'
import App from './views/App'
import router from './router/main'
import store from './store/main'
import VueAxios from 'vue-axios'
import axios from 'axios'
import VueI18n from 'vue-i18n'
import i18n from './il8n'
import auth from '@websanova/vue-auth/dist/v2/vue-auth.esm.js';
import driverAuthBearer from '@websanova/vue-auth/dist/drivers/auth/bearer.esm.js';
import driverHttpAxios from '@websanova/vue-auth/dist/drivers/http/axios.1.x.esm.js';
import driverRouterVueRouter from '@websanova/vue-auth/dist/drivers/router/vue-router.2.x.esm.js';
import driverOAuth2Google from '@websanova/vue-auth/dist/drivers/oauth2/google.esm.js';
import driverOAuth2Facebook from '@websanova/vue-auth/dist/drivers/oauth2/facebook.esm.js';

driverOAuth2Google.params.client_id = '547886745924-4vrbhl09fr3t771drtupacct6f788566.apps.googleusercontent.com';
driverOAuth2Facebook.params.client_id = '196729390739201';


import './app.scss'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.config.productionTip = false

Vue.use(auth, {
  plugins: {
      http: axios, // Axios
      // http: Vue.http, // Vue Resource
      router: router,
  },
  drivers: {
      auth: driverAuthBearer,
      http: driverHttpAxios,
      router: driverRouterVueRouter,
      oauth2: {
          google: driverOAuth2Google,
          facebook: driverOAuth2Facebook,
      }
  },
  options: {
      rolesKey: 'type',
      notFoundRedirect: {name: 'user-account'},
  }
});

Vue.use(VueAxios, axios)
Vue.axios.defaults.baseURL = `http://127.0.0.1:8000/api`;
Vue.use(VueI18n)

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount("#root");