

<template>
    <div class="rooms__container">
        <div class="box">
            <div class="options">
                <button class="option" @click="changeScreen(0)">LISTA PARTIDAS</button>
                <button class="option" @click="changeScreen(1)">CREAR PARTIDAS</button>
            </div>
            <div class="rooms" v-if="screen == 0">
                <div class="room">
                    <h2 class="title">Partida Clase #1</h2>
                    <h3 class="user">Julian</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">Hikkimikki</h2>
                    <h3 class="user">Deividson</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

                <div class="room">
                    <h2 class="title">Que plasta</h2>
                    <h3 class="user">Yoyo</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

                <div class="room">
                    <h2 class="title">Partida Clase #2</h2>
                    <h3 class="user">Dabid Visval</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">La Ganga</h2>
                    <h3 class="user">ElBryan</h3>
                    <h4 class="capacity">1/2</h4>
                    <button class="join-btn">Unirse</button>
                </div>

                <div class="room">
                    <h2 class="title">Me gusta la perassss</h2>
                    <h3 class="user">MrPera</h3>
                    <h4 class="capacity">2/2</h4>
                    <p>Completa</p>
                </div>

            </div>

            <div class="createRoom" v-if="screen == 1">
                <div class="info__container">
                    <div class="room__info">
                        <div class="info__box">
                            <label for="name">Nombre de la Sala:</label>
                            <input type="text">
                        </div>

                        <div class="info__box">
                            <label for="password">Contraseña:</label>
                            <input type="password">
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
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            screen: 1,
        }
    },
    methods: {
        changeScreen(index) {
            this.screen = index;
        }
    },
}
</script>

<style scoped>
.rooms__container {
    width: 100%;
    height: 100vh;
    background-image: url(../../public/img/pixelArt-background.jpeg);
    background-size: cover;
    display: flex;
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
    border: 2px solid #E58D08;
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
    border: 2px solid #E58D08;
}

.title {
    text-align: center;
}

.join-btn {
    padding: 5px 10px;
    background-color: #A1FB4F;
    color: black;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.join-btn:hover {
    background-color: #E58D08;
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
    background: #E58D08;
    border-radius: 100px;
    margin-right: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #282828;
}

.createRoom {
    background-color: transparent;
    width: 100%;
    height: 100%;
    border-radius: 15px;
    padding: 30px;
    margin-top: 10px;
}

.info__container {
    width: 100%;
    height: 100%;
    background-color: #282828;
    border-radius: 15px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

label {
    color: white;
    font-weight: bold;
    font-size: 20px;
}

.room__info {
    display: flex;
    gap: 30px;
}

input {
    background-color: transparent;
    border-bottom: 2px solid white;
    color: white;
    margin-left: 10px;
}

input:focus {
    outline: none;
}

select {
    background-color: transparent;
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
    background-color: #E58D08;
    color: white;
    border-bottom: 2px solid white;
    margin-left: 10px;
}
option:checked {
    display: none;
}

.privacity{
    font-size: 20px;
    font-weight: bold;
}
.privacity select{
    margin: 0;
}

</style>
