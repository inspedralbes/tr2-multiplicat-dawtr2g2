<template>
    <div class="container__game">
        <button class="nes-btn sortir" @click="sortir">Surt</button>

        <div class="game">
            <header class="header">
                <div class="player player1">
                    <img :src="`/vue/characters/${room.players[0].skin}_face.png`" alt="">
                    <div class="info">
                        <div class="bar">
                            <div class="progress" :class="{ 'low-life': room.players[0].life < 30 }"
                                :style="{ width: room.players[0].life + '%', background: room.players[0].life < 30 ? 'red' : room.players[0].life < 60 ? 'yellow' : '' }">
                            </div>
                        </div>
                        <p class="name">{{ room.players[0].name }}</p>
                    </div>
                </div>

                <TransitionGroup name="beat" mode="out-in">
                    <div class="timer">
                        <transition name="fade" mode="out-in">
                            <p :key="timer" class="time" :style="timerColor">{{ timer }}</p>
                        </transition>
                    </div>
                </TransitionGroup>

                <div class="player player2" v-if="room.players.length == 2">
                    <div class="info">
                        <div class="bar">
                            <div type="range" class="progress" :class="{ 'low-life': room.players[1].life < 30 }"
                                :style="{ width: room.players[1].life + '%', background: room.players[1].life < 30 ? 'red' : room.players[1].life < 60 ? 'yellow' : '' }">
                            </div>
                        </div>
                        <p class="name">{{ room.players[1].name }}</p>
                    </div>
                    <img :src="`/vue/characters/${room.players[1].skin}_face.png`" alt="">
                </div>
            </header>

            <main>
                <div class="character">
                    <img :src="`/vue/characters/${room.players[0].skin}_fight.png`" alt="">
                </div>
                <div class="question__container">
                    <h2 class="tematica">GEOMETRIA</h2>
                    <H3 class="question">{{ quest.pregunta }}</H3>
                    <h3 class="question" v-if="room.players.length == 1"> {{ quest }}</h3>
                    <h3 class=" turn" v-if="turn && !quest.pregunta && !questionSelected && room.players.length == 2">Et
                        toca tirar</h3>
                    <h3 class=" turn" v-if="!turn && !questionSelected && room.players.length == 2">Esperant atac</h3>
                    <h3 class=" turn" v-if="!turn && questionSelected && room.players.length == 2">Esperant resposta</h3>

                    <h4 v-if="showEst" :class="{ correct: est === 'Correcte', incorrect: est === 'Incorrecte' }">{{ est }}
                    </h4>
                </div>
                <div class="character" v-if="room.players.length == 2" style="transform: scaleX(-1);">
                    <img :src="`/vue/characters/${room.players[1].skin}_fight.png`" alt="">
                </div>
            </main>

            <footer class="cards" v-if="room.players.length == 2 && room.timeUp == false">
                <div :class="['card', 'yellow', { 'card-mobile': isMobileDevice() }]" v-for="i in numQuest" :key="i"
                    @click="genQuest()" v-if="turn && this.mostResp == false">
                    <div class="level-bg"></div>
                    <p class="card-level">{{ Math.floor(Math.random() * 4) + 1 }}</p>
                    <img class="image" src="/img/geometry.png" alt="">
                    <h3 class="title">Geometria</h3>
                </div>
                <div :class="['ans', { 'ans-mobile': isMobileDevice() }]" v-for="(answer, index) in ans" :key="index"
                    @click="compAns(quest.id, answer.id)" v-if="turn && ans != {} && this.mostResp == true"
                    :value="answer.id">
                    <div class="level-bg"></div>
                    <p class="card-level">{{ index + 1 }}</p>
                    <h3 class="title-ans">{{ answer.resposta }}</h3>
                </div>
            </footer>

        </div>
    </div>
</template>
  
<script>
import { socket } from "@/socket";
import { useAppStore } from '../stores/app';
import { watch } from 'vue';

export default {

    data() {
        return {
            quest: 'Esperant jugador...',
            ans: [],
            room: {},
            numQuest: 10,
            turn: true,
            timer: 10,
            intervalId: null,
            player: "",
            mostResp: false,
            est: '',
            showEst: false,
            questionSelected: false,
        };
    },
    created() {
        const store = useAppStore();
        this.room = store.room;
    },
    mounted() {
        const store = useAppStore();


        if (!store.isLogged) {
            this.$router.push("/");
        }

        watch(() => store.questAct, request => {
            this.mostResp = true;
            this.quest = request;
        });

        watch(() => store.room, newRoom => {
            this.room = newRoom;
        })

        this.turn = store.getTurn();
        this.player = store.getUsername();

        watch(() => store.turn, newTurn => {
            this.turn = newTurn;
            this.timer = 15;
            if (this.turn == true) {
                this.mostResp = false;
            }
        });

        watch(() => store.respAct, answers => {
            if (answers.length != {}) {
                this.ans = answers;
                this.mostResp = true;
            }

        });

        socket.on('correct', () => {
            this.questionSelected = false;
            this.quest = '';
            this.est = 'Correcte'
            this.timer = 15;
            this.showEstForThreeSeconds();

        });

        socket.on('incorrect', () => {
            this.questionSelected = false;
            this.quest = '';
            this.est = 'Incorrecte'
            this.timer = 15;
            this.showEstForThreeSeconds();
        });

        socket.on('startTimer', () => {
            this.questionSelected = false;
            this.timer = 15;
            this.quest = '';
            this.startTimer();
        });

        watch(() => this.numQuest, newVal => {
            if (newVal == 0) {
                this.numQuest = 10;
            }
        });
    },
    beforeDestroy() {
        this.stopTimer();
    },
    methods: {

        genQuest() {
            this.questionSelected = true;
            this.est = '';
            this.numQuest--;
            socket.emit('genQuest', this.room.id);
            this.mostResp = true;
        },
        compAns(quest, ans) {
            this.questionSelected = false;
            this.mostResp = false;
            this.ans = [];
            this.est = '';
            socket.emit('compAns', quest, ans, this.room.id, this.player);
        },
        showEstForThreeSeconds() {
            this.showEst = true;
            setTimeout(() => {
                this.showEst = false;
            }, 3000);
        },
        sortir() {
            socket.emit('exitRoom', this.room.id);
        },
        startTimer() {
            const store = useAppStore();
            this.intervalId = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    this.stopTimer();
                    socket.emit('timerUp', this.room.id);
                    if (this.turn == true && this.ans.length == 4) {
                        socket.emit('noAnswer', this.room.id, this.player);
                    }
                }
            }, 1000);
        },
        stopTimer() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
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
    computed: {
        timerColor() {
            if (this.timer < 4) {
                return {
                    color: 'red'
                }
            } else {
                return {
                    color: 'black'
                }
            }
        }
    }

};
</script>
  
<style scoped>
.correct {
    color: green;
    font-size: 30px;
}

.incorrect {
    color: red;
    font-size: 30px;
}

.turn {
    font-size: 30px;
    animation: blink 1.3s linear infinite;
}

@keyframes blink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif !important;
}

.container__game {
    background-size: cover;
    background-position: 0 60%;
    background-image: url('/img/combate.jpg');
    height: 100vh;
    display: flex;
}

.game {
    width: 100%;
    /* height: 100vh; */
    display: grid;
    grid-template-rows: repeat(3, 1fr);
    align-items: center;
    justify-items: center;
    background-color: rgba(0, 0, 0, 0.9);
}


/* -------------------------------------------------------------------------- */
/*                                   HEADER                                   */
/* -------------------------------------------------------------------------- */
.header {
    width: 90%;
    display: grid;
    grid-template-columns: 2fr 1fr 2fr;
    justify-items: center;
    align-items: center;
}

.player {
    display: flex;
    width: 100%;
}

.player img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: 0 10px;
}

.info {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.player2 .info {
    text-align: right;
}

.bar {
    display: flex;
    flex-direction: row;
    width: 100%;
    height: 12px;
    background-color: #b1b2b5;
    border-radius: 100px;
    margin-top: 45px;
}

.low-life {
    animation-name: lowLife;
    animation-duration: 1s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;

    -webkit-animation-name: lowLife;
    -webkit-animation-duration: 1s;
    -webkit-animation-timing-function: linear;
    -webkit-animation-iteration-count: infinite;
}

.player1 .bar .progress {
    width: 100%;
    height: 100%;
    background-color: #44d953;
    border-radius: 5px;
}

@-moz-keyframes lowLife {
    0% {
        opacity: 1.0;
    }

    50% {
        opacity: 0.0;
    }

    100% {
        opacity: 1.0;
    }
}

@-webkit-keyframes lowLife {
    0% {
        opacity: 1.0;
    }

    50% {
        opacity: 0.0;
    }

    100% {
        opacity: 1.0;
    }
}

@keyframes lowLife {
    0% {
        opacity: 1.0;
    }

    50% {
        opacity: 0.0;
    }

    100% {
        opacity: 1.0;
    }
}

.player2 .bar {
    flex-direction: row-reverse;
}

.player2 .bar .progress {
    width: 100%;
    height: 100%;
    background-color: #44d953;
    border-radius: 5px;
}

.player .name {
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    border-radius: 0 0 10px 10px;
}

.player .level {
    font-size: 14px;
    font-weight: 600;
    color: #e76441;
}

.timer {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 100px;
    color: black;
    background-color: #fff;
    border-radius: 50%;
    text-align: center;
}

.timer .time {
    font-size: 60px;
    font-weight: bold;
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.timer .title {
    font-size: 14px;
}

.title {
    text-align: center;
}

/* -------------------------------------------------------------------------- */
/*                                    MAIN                                    */
/* -------------------------------------------------------------------------- */
main {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;

}

.character {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.character img {
    width: 50%;
    height: auto;
    object-fit: contain;
}

.question__container {
    color: #fff;
    width: 100%;
    text-align: center;
}

.question__container .tematica {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
    border-bottom: 4px solid #f4b44d;
    display: inline-block;
}

.question__container .question {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
}



/* -------------------------------------------------------------------------- */
/*                                    CARDS                                   */
/* -------------------------------------------------------------------------- */
.cards {
    display: flex;
    gap: 10px;
    overflow: auto;
    width: 90%;
    justify-content: center;
    align-items: center;
}

.card {
    position: relative;
    width: 150px;
    height: 200px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    border: 8px solid white;
    overflow: hidden;
    transition: all .3s ease-in-out;

}

.card-mobile {
    font-size: 8px;
    width: 100%;
    height: 100px;
    border: 3px solid white;
    border-radius: 5px;
}

.level-bg {
    position: absolute;
    top: -5px;
    left: -15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid white;
}

.card-level {
    position: absolute;
    top: 5px;
    left: 5px;
    font-size: 16px;
    font-weight: bold;
}

.ans {
    position: relative;
    width: 150px;
    height: 200px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    overflow: hidden;
    background-image: url(/img/card-background.jpg);
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    z-index: 2;
    transition: all .3s ease-in-out;
}

.ans-mobile {
    font-size: 5px;
    width: 75px;
    height: 100px;
    /* border: 3px solid white; */
    border-radius: 5px;
}

.title-ans {
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 20px;
}

.card:hover {
    cursor: pointer;
    transform: translateY(-30px);
}


.ans::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: -2;
    border-radius: 20px;
}


.ans:hover {
    cursor: pointer;
    transform: translateY(-30px);
}


.image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}


.yellow {
    background-color: #f4b44d;
}

.red {
    background-color: #ff6464;
}

.blue {
    background-color: #5ad5db;
}

.sortir {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 3%;
    right: 5%;
    width: 6vw;
}

.sortir::after {
    box-shadow: inset -4px -4px #e46d3a !important;
}

.sortir:hover {
    background-color: #ec9e50 !important;
}

.sortir:hover::after {
    box-shadow: inset -6px -6px #e46d3a !important;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a !important;
}

/* TRANSITIONS */
.fade-enter-active,
.fade-leave-active {
    transition: all .5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
  