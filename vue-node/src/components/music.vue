<template>
    <button class="nes-btn" @click="toggleMuteAndPlay">
        <img :src="isMuted
            ? '/vue/img/mute-sound.png'
            : '/vue/img/unmute-sound.png'
            " alt="" />
    </button>
</template>

<script>
export default {
    data() {
        return {
            audio: null,
            isMuted: true,
            userInteracted: false,
        };
    },
    methods: {
        toggleMuteAndPlay() {
            if (!this.userInteracted) {
                this.userInteracted = true;
                this.initAudio();
            }
            this.isMuted = !this.isMuted;
            this.audio.muted = this.isMuted;
            if (!this.isMuted) {
                this.audio.play().catch(error => {
                    console.error('Error al reproducir audio:', error);
                });
            }
        },
        initAudio() {
            this.audio = new Audio("/vue/audio/coral-chorus.mp3");
            this.audio.loop = true;
        },
    },
    mounted() {
        // Init audio when the component is mounted
        this.initAudio();
    },
};
</script>

<style scoped>
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
