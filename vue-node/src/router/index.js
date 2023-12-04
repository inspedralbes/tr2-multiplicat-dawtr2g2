import { createRouter, createWebHistory } from 'vue-router'
import Battle from '../views/BattleView.vue'
import Landing from '../views/LandingView.vue'
import Battlemath from '../views/battlemathGame.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'landing',
      component: Landing
    },
    {
      path: '/battle',
      name: 'battle',
      component: Battle
    },
    {
      path: '/game',
      name: 'battlemath',
      component: Battlemath
    }
  ]
})

export default router
