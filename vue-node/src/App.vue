<script setup>
import { onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';

const playMusic = () => {
  const audio = new Audio("/audio/coral-chorus.mp3");
  audio.loop = true;
  audio.play();
};

onMounted(() => {
  playMusic();
});
</script>

<template>
  <RouterView />
</template>
