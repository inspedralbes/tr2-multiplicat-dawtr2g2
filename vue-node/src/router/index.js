import { createRouter, createWebHistory } from 'vue-router'
import Game from '../components/battlemathGame.vue'
import Battle from '../views/BattleView.vue'
import Landing from '../views/LandingView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'landing',
      component: Landing
    },
    {
      path: '/game',
      name: 'game',
      component: Game
    },
    {
      path: '/battle',
      name: 'battle',
      component: Battle
    }
  ]
})

export default router
