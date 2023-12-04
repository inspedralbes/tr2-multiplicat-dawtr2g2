import { createRouter, createWebHistory } from 'vue-router'
import test from '../components/test.vue'
import lleno from '../components/lleno.vue'
import partida from '../components/partida.vue'
import Battle from '../views/BattleView.vue'
import Landing from '../views/LandingView.vue'
import Battlemath from '../views/battlemathGame.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: test
    },
    {
      path: '/lleno',
      name: 'lleno',
      component: lleno
    },
    {
      path: '/partida',
      name: 'partida',
      component: partida
    },
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
