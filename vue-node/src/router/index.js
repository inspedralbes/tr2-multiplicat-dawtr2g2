import { createRouter, createWebHistory } from 'vue-router'
import test from '../components/test.vue'
import lleno from '../components/lleno.vue'
import partida from '../components/partida.vue'
import Battle from '../views/BattleView.vue'
import Landing from '../views/LandingView.vue'
import Login from '../views/Login.vue'
import Battlemath from '../views/battlemathGame.vue'
import Rooms from '../views/RoomsView.vue'
import Register from '../views/RegisterView.vue'
import Characters from '../components/char_select.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
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
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
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
    },
    {
      path: '/rooms',
      name: 'rooms',
      component: Rooms
    },
    {
      path: '/characters',
      name: 'characters',
      component: Characters
    }
  ]
})

export default router
