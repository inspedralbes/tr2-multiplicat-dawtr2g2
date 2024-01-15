<script setup>
import { ref, onMounted } from "vue";
import { RouterLink, RouterView } from "vue-router";
import Music from "./components/music.vue";

</script>

<template>
    <Music />
    <RouterView />
</template>

