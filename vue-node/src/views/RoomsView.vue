<template>
    <div>
        <button class="option tornar" @click="returnGame">Torna al joc</button>
        <div class="rooms__container">
            <div :class="['box', { 'box-mobile': isMobileDevice() }]">
                <div class="options">
                    <button class="option" :class="{ active: screen === 0 }" @click="changeScreen(0)">
                        LLISTA PARTIDES
                    </button>
                    <button class="option" :class="{ active: screen === 1 }" @click="changeScreen(1)">
                        CREAR PARTIDES
                    </button>
                </div>
                <div :class="['rooms', { 'rooms-mobile': isMobileDevice() }]" v-if="screen == 0">
                    <div style="width: 100%" v-for="room in rooms" :key="room">
                        <div class="room" v-if="room.players.length < 2">
                            <h2 class="title">{{ room.name }}</h2>
                            <h3 class="user">{{ room.players[0].name }}</h3>
                            <h4 class="capacity">
                                {{ room.players.length }}/2
                            </h4>
                            <button class="join-btn" value="{{room.id}}" @click="joinRoom(room.id)">
                                Unir-se
                            </button>
                        </div>
                    </div>
                </div>
                <div class="createRoom" v-if="screen == 1">
                    <div :class="['info__container', { 'info__container-mobile': isMobileDevice() }]">
                        <div class="room__info">
                            <div class="info__box">
                                <label for="name">Nom de la sala:</label>
                                <input type="text" maxlength="20" v-model="this.room" />
                            </div>
                        </div>
                        <div class="privacity">
                            <button class="create-btn" @click="createRoom()">
                                Crear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { socket } from "@/socket";
import { useAppStore } from "../stores/app";
import { watch } from "vue";
import { uid } from "uid";

export default {
    data() {
        return {
            room: "",
            rooms: [],
            screen: 1,
            player: {
                name: "",
                skin: "",
            }
        };
    },
    created() {
        const store = useAppStore();
        if (!store.isLogged) {
            this.$router.push("/");
        }


        store.setLastRoute("/rooms");
    },
    mounted() {
        socket.emit("getRooms");
        const store = useAppStore();


        watch(
            () => store.rooms,
            (newVal) => {
                this.rooms = newVal;
            }
        );
        this.player.name = store.getUsername();
        this.player.skin = store.getSkin();
    },
    methods: {
        createRoom() {
            socket.emit("createRoom", this.room, uid(), this.player);
            this.room = "";
            this.rooms = [];
        },
        joinRoom(id) {
            socket.emit("joinRoom", id, this.player);
        },
        changeScreen(index) {
            this.screen = index;
        },
        returnGame() {
            this.$router.push("/game");
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
.rooms__container {
    width: 100%;
    height: 100vh;
    background-image: url('/img/rooms_bg.jpg');
    background-size: cover;
    display: flex;
    background-position: 0 60%;
    align-items: center;
}

.box {
    position: relative;
    width: 70%;
    height: 35%;
    border: 4px solid black;
    margin: 0 auto;
    border-radius: 20px;
}

.box-mobile {
    margin-top: 30px;
}

.options {
    position: absolute;
    top: -30px;
    left: 40px;
    display: flex;
}

.option {
    background-color: #282828;
    text-transform: uppercase;
    color: white;
    font-weight: bold;
    padding: 15px 10px;
    border: 2px solid #e58d08;
    border-radius: 15px;
    margin-left: 20px;
}

.rooms {
    margin-top: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    height: 270px;
    overflow-y: scroll;
}

.room {
    width: 90%;
    min-height: 58px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: center;
    justify-items: center;
    margin: 0 auto;
    background-color: #282828;
    color: white;
    border-radius: 10px;
    border: 2px solid #e58d08;
}

.rooms-mobile {
    height: 100%;
}

.title {
    text-align: center;
}

.join-btn {
    padding: 5px 10px;
    background-color: #a1fb4f;
    color: black;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.join-btn:hover {
    background-color: #e58d08;
    color: white;
}

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 100px;
    margin-right: 5px;
}

::-webkit-scrollbar-thumb {
    background: #e58d08;
    border-radius: 100px;
    margin-right: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #282828;
}

.createRoom {
    background-color: rgba(0, 0, 0, 0.5);
    width: 100%;
    height: 100%;
    border-radius: 15px;
    padding: 30px;
}

.info__container {
    margin-top: 10px;
    width: 100%;
    height: 100%;
    background-color: #282828;
    border-radius: 15px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
}

.info__container-mobile {
    height: 100% !important;
}

label {
    color: white;
    font-weight: bold;
    font-size: 20px;
}

.room__info {
    display: flex;
    gap: 10%;
}

button {
    border: 0;
}

input {
    background-color: transparent;
    border: 0;
    border-bottom: 2px solid white;
    color: white;
    margin-left: 10px;
}

input:focus {
    outline: none;
}

select {
    background-color: transparent;
    border: 0;
    border-bottom: 2px solid white;
    color: white;
    margin-left: 10px;
}

select:focus {
    outline: none;
}

option {
    background-color: #282828;
    color: white;
    border-bottom: 2px solid white;
    margin-left: 10px;
}

option:focus {
    background-color: #e58d08;
    color: white;
    border-bottom: 2px solid white;
    margin-left: 10px;
}

option:checked {
    display: none;
}

.privacity {
    font-size: 20px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
}

.privacity select {
    margin: 0;
    background-color: rgb(136, 136, 136);
    padding: 10px;
    border-radius: 10px;
    border: 2px solid transparent;
}

.active {
    background-color: #e58d08;
    color: white;
    border: 2px solid transparent;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.glass {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.create-btn {
    padding: 5px 30px;
    background-color: #a1fb4f;
    color: black;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.create-btn:hover {
    background-color: #e58d08;
    color: white;
}

@media screen and (max-height: 930px) {
    .info__container {
        height: 40vh;
    }

    .box {
        height: 50%;
    }
}

.tornar {
    position: fixed;
    top: 3%;
    left: 8%;
}
</style>
