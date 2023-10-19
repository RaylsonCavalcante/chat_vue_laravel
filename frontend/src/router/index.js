import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import Guard from '../services/middleware.js'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      beforeEnter: Guard.existsAuth
    },
    {
      path: '/home',
      name: 'home',
      beforeEnter: Guard.noExistsAuth,
      component: () => import('../views/HomeView.vue')
    }
  ]
})

export default router
