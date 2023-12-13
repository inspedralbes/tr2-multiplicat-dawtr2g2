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
        <div class="landing">
            <h1>BattleMath</h1>
            <p>Lluita amb els teus coneixements de matemàtiques</p>
            <button class="pixel-art-button" @click="irARuta()">Go</button>
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

.landing {
    background-color: white;
    width: 50vw;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 250px auto;
    border: 15px solid black;
    border-radius: 10px;
    padding: 20px;
}

.pixel-art-button {
    background-color: #4caf50;
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 10px;
    cursor: pointer;
    border-radius: 4px;
    position: relative;
    overflow: hidden;
}

/* .pixel-art-button::before {
    content: '';
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    background-image: url('ruta/a/imagen_pixel_art.png');
    background-size: cover;
    z-index: -1;
} */
</style>
