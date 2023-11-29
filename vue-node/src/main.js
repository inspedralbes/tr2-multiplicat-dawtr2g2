import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { defineCustomElements as defineIonPhaser } from '@ion-phaser/core/loader';

import App from './App.vue'
import router from './router'
import Phaser from 'phaser';

defineIonPhaser(window);

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.config.compilerOptions.isCustomElement = (tag) => tag.startsWith('ion-');
app.config.globalProperties.$phaser = Phaser;

app.mount('#app')
