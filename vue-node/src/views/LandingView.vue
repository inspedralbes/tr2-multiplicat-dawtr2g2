<script>
import Preloader from "../components/preloader.vue";
import router from "../router";

export default {
    components: {
        Preloader,
    },
    methods: {
        irARuta() {
            router.push("/game");
        },
    },
};
</script>

<template>
    <Preloader />

    <div class="landing__container">
        <div class="flexCenter">

            <div class="nes-container is-rounded landing">
                <h1>BattleMath</h1>
                <p>Lluita amb els teus coneixements de matemàtiques</p>
                <button class="nes-btn" @click="irARuta()">Jugar</button>
            </div>
            <div class="art-credits nes-container is-rounded">
                <span class="art-credits-text">Pixelart sprites made by <a
                        href="https://www.patreon.com/pixelarchipel">PIXEL ARCHIPEL</a></span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.landing__container {
    position: absolute;
    background-image: url("https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/01073865290819.5d61d475f0072.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    width: 100vw;
    height: 100vh;
}

.flexCenter {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    height: 80vh;
}

.art-credits {
    position: absolute;
    bottom: 20%;
    padding: 20px;
    font-size: larger;
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
