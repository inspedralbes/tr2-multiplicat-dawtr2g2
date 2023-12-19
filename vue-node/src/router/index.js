import { createRouter, createWebHistory } from 'vue-router'
import partida from '../components/partida.vue'
import Landing from '../views/LandingView.vue'
import Battlemath from '../views/battlemathGame.vue'
import Rooms from '../views/RoomsView.vue'
import Register from '../components/Register.vue'
import Loading from '../views/Loading.vue'
import textBox from '../components/textBox.vue'
import gameOver from '../views/gameOver.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
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
      path: '/endGame',
      name: 'endGame',
      component: gameOver
    }
  ]
})

export default router
