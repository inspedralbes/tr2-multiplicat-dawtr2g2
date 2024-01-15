

<template>
    <Preloader />

    <div :class="['landing__container', , { 'landing__container-mobile': isMobileDevice() }]">
        <div :class="['flexCenter', { 'flexCenter-mobile': isMobileDevice() }]">
            <div class="nes-container is-rounded landing">
                <h1>BattleMath</h1>
                <p>Lluita amb els teus coneixements de matemàtiques</p>
                <button class="nes-btn" @click="irARuta()">Jugar</button>
                <!-- <button id="play" @click="playMusic()">Play</button> -->
            </div>
            <div
                :class="['nes-container', 'is-rounded', { 'art-credits': !isMobileDevice() }, { 'art-credits-mobile': isMobileDevice() }]">
                <span class="art-credits-text">Pixelart sprites made by
                    <a href="https://www.patreon.com/pixelarchipel" target="_blank">PIXEL ARCHIPEL</a></span>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../components/preloader.vue";
import Music from "../components/music.vue";
import router from "../router";
import { useAppStore } from "../stores/app";

export default {
    components: {
        Preloader,
        Music,

    },
    mounted() {
        const store = useAppStore();
        store.setLastRoute("/landing");
    },
    methods: {
        irARuta() {
            router.push("/game");
        },
        isMobileDevice() {
            const userAgent = navigator.userAgent;
            const mobileKeywords = [
                'Android',
                'webOS',
                'iPhone',
                'iPad',
                'iPod',
                'BlackBerry',
                'Windows Phone'
            ];

            return mobileKeywords.some(keyword => userAgent.includes(keyword));
        }
    },


};
</script>
<style scoped>
.landing__container {
    position: absolute;
    background-image: url("/img/landing_1.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    width: 100vw;
    height: 100vh;
    background-position: 0 30%;
}

.landing__container-mobile {
    display: flex;
    justify-content: center;
    align-items: center;
}

.flexCenter {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    height: 80vh;
}

.flexCenter-mobile {
    height: auto;
}

.flexCenter-mobile>div {
    margin: 10px;
}

.art-credits {
    position: absolute;
    bottom: 20%;
    padding: 20px;
    font-size: larger;
    background-color: rgba(255, 255, 255, 0.7);
}

.art-credits-mobile {
    background-color: rgba(255, 255, 255, 0.7);
}

.landing {
    background-color: white;
    font-size: 25px;
    width: 50vw;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 30px;
}

button {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
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

.nes-container.is-rounded {
    border-image-repeat: stretch !important;
}
</style>
