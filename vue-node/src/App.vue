<script setup>
import { ref, onMounted } from "vue";
import { RouterLink, RouterView } from "vue-router";

const audio = new Audio("/audio/coral-chorus.mp3");
audio.loop = true;

const isMuted = ref(false);

const toggleMute = () => {
    isMuted.value = !isMuted.value;
    audio.muted = isMuted.value;
};

onMounted(() => {
    audio.play();
});
</script>

<template>
    <button class="nes-btn" @click="toggleMute">
        <img
            :src="
                isMuted
                    ? '/public/img/mute-sound.png'
                    : '/public/img/unmute-sound.png'
            "
            alt=""
        />
    </button>
    <RouterView />
</template>

<style>
button {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1;
    width: 50px;
    height: auto;
}

img {
    width: 100%;
    height: 100%;
}

button {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
    margin-top: 25px;
}

button::after {
    box-shadow: inset -4px -4px #e46d3a !important;
}

button:hover {
    background-color: #ec9e50 !important;
}

button:hover::after {
    box-shadow: inset -6px -6px #e46d3a !important;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a !important;
}
</style>
