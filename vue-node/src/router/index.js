import { createRouter, createWebHistory } from 'vue-router'
import test from '../components/test.vue'
import lleno from '../components/lleno.vue'
import partida from '../components/partida.vue'
import Landing from '../views/LandingView.vue'
import Login from '../components/Login.vue'
import Battlemath from '../views/battlemathGame.vue'
import Rooms from '../views/RoomsView.vue'
import Register from '../components/Register.vue'
import Loading from '../views/Loading.vue'
import textBox from '../components/textBox.vue'

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
      path: '/game',
      name: 'battlemath',
      component: Battlemath
    },
    {
      path: '/loading',
      name: 'loading',
      component: Loading
    },
    {
      path: '/rooms',
      name: 'rooms',
      component: Rooms
    }
  ]
})

export default router
