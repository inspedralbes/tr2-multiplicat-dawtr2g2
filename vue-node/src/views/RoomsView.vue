<template>
    <div>
        <button class="option tornar" @click="returnGame">Torna al joc</button>
        <div class="rooms__container">
            <div class="box">
                <div class="options">
                    <button class="option" :class="{ active: screen === 0 }" @click="changeScreen(0)">
                        LISTA PARTIDAS
                    </button>
                    <button class="option" :class="{ active: screen === 1 }" @click="changeScreen(1)">
                        CREAR PARTIDAS
                    </button>
                </div>
                <div class="rooms" v-if="screen == 0">
                    <div style="width: 100%" v-for="room in rooms" :key="room">
                        <div class="room" v-if="room.players.length < 2">
                            <h2 class="title">{{ room.name }}</h2>
                            <h3 class="user">{{ room.players[0].name }}</h3>
                            <h4 class="capacity">
                                {{ room.players.length }}/2
                            </h4>
                            <button class="join-btn" value="{{room.id}}" @click="joinRoom(room.id)">
                                Unirse
                            </button>
                        </div>
                    </div>
                </div>
                <div class="createRoom" v-if="screen == 1">
                    <div class="info__container">
                        <div class="room__info">
                            <div class="info__box">
                                <label for="name">Nombre de la Sala:</label>
                                <input type="text" v-model="this.room" />
                            </div>
                            <div class="info__box">
                                <label for="password">Contraseña:</label>
                                <input type="password" />
                            </div>
                        </div>
                        <div class="info__box">
                            <label for="category">Categoria:</label>
                            <select name="category" id="category">
                                <option value="1">Calcul</option>
                                <option value="2">Geometria</option>
                                <option value="3">Mesures</option>
                            </select>
                        </div>
                        <div class="info__box">
                            <label for="dificult">Dificultad:</label>
                            <select name="dificult" id="dificult">
                                <option value="1">Facil</option>
                                <option value="2">Mitj</option>
                                <option value="3">Dificil</option>
                            </select>
                        </div>
                        <div class="privacity">
                            <div class="info__box">
                                <select name="privacity" id="privacity">
                                    <option value="1">Publica</option>
                                    <option value="2">Privada</option>
                                </select>
                            </div>
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

    mounted() {
        socket.emit("getRooms");
        const store = useAppStore();

        if (!store.isLogged) {
            this.$router.push("/");
        }

        watch(
            () => store.rooms,
            (newVal) => {
                this.rooms = newVal;
            }
        );
        this.player.name = store.getUsername();
        this.player.skin = store.getSkin();
        console.log(this.$router);
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
    left: 3%;
}
</style>
