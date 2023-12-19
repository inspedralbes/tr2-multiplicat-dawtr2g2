<template>
    <div>
        <div class="win" v-if="win == true">
            <h1>VICTORIA</h1>
            <img :src="`/characters/${skin}_fight.png`" alt="">
            <button class="nes-btn" @click=tornarRooms()>Tornar</button>
            <img class="confetti" src="/img/confetti-gif-8.gif" alt="">
            <img class="confetti" src="https://i.pinimg.com/originals/fd/b0/9c/fdb09cd5e747f5c8330f998f11efb0a1.gif" alt="">
        </div>

        <div class="lose" v-if="win == false">
            <h1>DERROTA</h1>
            <img :src="`/characters/${skin}_fight.png`" alt="">
            <button class="nes-btn" @click="tornarRooms()">Tornar</button>
            <img class="lluvia" src="/img/lluvia.gif" alt="">
        </div>

    </div>
</template>

<script>
import { useAppStore } from '../stores/app';
import router from '../router'

export default {
    
    name: "gameOver",
    data() {
        return {
            win: true,
            skin: '',
        };
    },
    mounted() {
        const appStore = useAppStore();
        this.win = appStore.gameOver();
        this.skin = appStore.user.skin;
    },
    methods: {
        tornarRooms(){
            const store = useAppStore();
            router.push('/rooms');
            store.room = null;
        }
    },
};
</script>

<style scoped>
.win, .lose {
    background-color: #1e2736;
    width: 100%;
    height: 100vh;
    display: grid;
    grid-template-rows: repeat(3, 1fr);
    align-items: center;
    justify-items: center;
    position: relative;
}

h1 {
    color: #fff;
    font-size: 5rem;
}

img {
    width: 15%;
    height: auto;
}

button {
    border-image-repeat: stretch;
    background-color: #ffad5d;
}

button::after {
    box-shadow: inset -4px -4px #e46d3a;
}

button:hover {
    background-color: #ec9e50;
}

button:hover::after {
    box-shadow: inset -6px -6px #e46d3a;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a;
}

.confetti {
    width: auto;
    height: 70vh;
    position: absolute;
    top: 0;
    z-index: 1;
    object-fit: cover;
}

.lluvia {
    width: 40%;
    height: 70vh;
    position: absolute;
    top: 0;
    z-index: 1;
    object-fit: cover;
}
</style>