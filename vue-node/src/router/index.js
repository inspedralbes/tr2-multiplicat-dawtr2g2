import { createRouter, createWebHistory } from 'vue-router'
// import Game from '../views/GameView.vue'
import Battle from '../views/BattleView.vue'
import Landing from '../views/LandingView.vue'
import Login from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'landing',
      component: Landing
    },
    // {
    //   path: '/game',
    //   name: 'game',
    //   component: Game
    // },
    {
      path: '/battle',
      name: 'battle',
      component: Battle
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    }
  ]
})

export default router
