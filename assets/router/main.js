import Vue from 'vue'
import VueRouter from 'vue-router'
import Landing from '../views/Landing'
import Login from '../views/Login'
import Register from '../views/Register'
import Dashboard from '../views/Dashboard/Dashboard'
import DashboardProperty from '../views/Dashboard/Property/index'
import DashboardContract from '../views/Dashboard/Contract/index'
import DashboardMessage from '../views/Dashboard/Message/index'
import DashboardPropertyHunt from '../views/Dashboard/PropertyHunt/index'
import DashboardRating from '../views/Dashboard/Rating/index'
import DashboardAccount from '../views/Dashboard/Account/index'
import DashboardPropertyCreate from '../views/Dashboard/Property/create'
import store from '../store/main'

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
    component: Dashboard,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/contracts',
    name: 'DashboardContracts',
    component: DashboardContract,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/properties',
    name: 'DashboardProperties',
    component: DashboardProperty,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/properties/create',
    name: 'DashboardPropertyCreate',
    component: DashboardPropertyCreate,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/messages',
    name: 'DashboardMessages',
    component: DashboardMessage,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/property-hunt',
    name: 'DashboardPropertyHunt',
    component: DashboardPropertyHunt,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/ratings',
    name: 'DashboardRatings',
    component: DashboardRating,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  },
  {
    path: '/dashboard/account',
    name: 'DashboardAccount',
    component: DashboardAccount,
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/isAuthenticated']) {
        return next({
          name: 'Login'
        })
      }
      next()
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
