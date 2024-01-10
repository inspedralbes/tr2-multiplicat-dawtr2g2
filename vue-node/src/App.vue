<script setup>
import { ref, onMounted } from "vue";
import { RouterLink, RouterView } from "vue-router";
import music from "./components/music.vue";

</script>

<template>
    <music />
    <RouterView />
</template>

