import Vue from 'vue'
import VueRouter from 'vue-router'
import Landing from '../views/Landing'
import Login from '../views/Login'
import Register from '../views/Register'
import Dashboard from '../views/Dashboard'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Landing',
    
    component: Landing
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router