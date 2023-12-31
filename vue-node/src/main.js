// import index from './index.css'
// Funciona con el import de abajo
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { defineCustomElements as defineIonPhaser } from '@ion-phaser/core/loader';
import piniaPluginPersistedState from "pinia-plugin-persistedstate"

import App from './App.vue'
import './index.css'
import router from './router'
import Phaser from 'phaser';


defineIonPhaser(window);

const app = createApp(App)
const pinia = createPinia()

pinia.use(piniaPluginPersistedState)

app.use(pinia)
app.use(router)

app.config.compilerOptions.isCustomElement = (tag) => tag.startsWith('ion-');
app.config.globalProperties.$phaser = Phaser;

app.mount('#app')
