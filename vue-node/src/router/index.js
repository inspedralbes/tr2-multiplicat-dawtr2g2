import { createRouter, createWebHistory } from 'vue-router'
import test from '../components/test.vue'
import lleno from '../components/lleno.vue'
import partida from '../components/partida.vue'

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
    }
  ]
})

export default router
