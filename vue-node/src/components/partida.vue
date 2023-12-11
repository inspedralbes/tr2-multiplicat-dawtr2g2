<template>
  <head>
      <link rel="stylesheet" href="style.css">
  </head>
  <div class="container__game">
      <div class="game">
          <header class="header">
              <div class="player player1">
                  <img src="../../public/img/monk2 face.png" alt="">
                  <div class="info">
                      <div class="bar">
                          <div class="progress"></div>
                      </div>
                      <p class="name">Ermengol Bota</p>
                      <p class="level">Lvl 7</p>
                  </div>
              </div>

              <div class="timer">
                  <p class="time">10</p>
                  <div class="title">
                      <p>TIME</p>
                  </div>
              </div>

              <div class="player player2" v-if="room.players.length == 2">
                  <div class="info">
                      <div class="bar">
                          <div class="progress"></div>
                      </div>
                      <p class="name">Pedro Garcia</p>
                      <p class="level">Lvl 9</p>
                  </div>
                  <img src="../../public/img/Skeleton Faceset.png" alt="">
              </div>
          </header>

          <main>
              <div class="character">
                  <img src="../../public/img/monk2 fight.png" alt="">
              </div>
              <div class="question__container" >
                  <h2 class="tematica">GEOMETRIA</h2>
                  <H3 class="question">{{ quest.pregunta }}</H3>
              </div>
              <div class="character" v-if="room.players.length == 2">
                  <img src="../../public/img/Skeleton Fight.png" alt="">
              </div>
          </main>

          <footer class="cards" v-if="room.players.length == 2">
              <!--
                <div class="card red">
                  <div class="level-bg"></div>
                  <p class="card-level">2</p>
                  <img class="image" src="img/mesures.png" alt="">
                  <h3 class="title">Mesures</h3>
              </div>
              -->

              <div class="card yellow" v-for="i in numQuest" :key="i" @click="genQuest()" v-if="ans.length == 0 && turn">
                  <div class="level-bg"></div>
                  <p class="card-level">3</p>
                  <img class="image" src="img/geometry.png" alt="">
                  <h3 class="title">Geometria</h3>
              </div>
              <!--
                <div class="card blue">
                  <div class="level-bg"></div>
                  <p class="card-level">1</p>
                  <img class="image" src="img/calculo.webp" alt="">
                  <h3 class="title">Calcul</h3>
              </div>
              -->
              <div class="ans" v-for="(answer, index) in ans" :key="index" @click="compAns(quest.id,answer.id)" :value="answer.id">
                <div class="level-bg"></div>
                <p class="card-level">{{ index + 1 }}</p>
                <h3 class="title-ans">{{ answer.resposta }}</h3>
              </div>
          </footer>

      </div>
  </div>
</template>


<!--
  <template>
  <div>
    <div>{{ quest.pregunta }}</div>
    <button @click="genQuest()">Generar Pregunta</button>
    
    <div>{{est}}</div>
  </div>
    
</template>
-->
  
  <script>
  import { socket } from "@/socket";
  import {useAppStore} from '../stores/app';
  import { watch } from 'vue';

  export default {
    
    data() {
      return {
        quest: '',
        ans: [],
        est: '',
        room: {},
        numQuest:10,
        turn: true,
      };
    },
    created() {
      const store = useAppStore();
      this.room = store.room;
    },
    mounted() {
        const store = useAppStore();
      
        watch(() => store.questAct, request => {
            this.quest = request;
        });

        watch(() => store.room, newRoom => {
            this.room = newRoom;
        })

        this.turn = store.getTurn();
        watch(() => store.turn, newTurn => {
            this.turn = newTurn;
        });

        watch(() => store.respAct, answers => {
            this.ans = answers;
        });

        socket.on('correct', () => {
            this.est = 'Correcte';
        });

        socket.on('incorrect', () => {
            this.est = 'Incorrecte';
        });
    },
    methods: {
      genQuest(){
        this.est = '';
        this.numQuest--;
        console.log(this.room)
        socket.emit('genQuest',this.room.id);
      },
      compAns(quest, ans){
        this.ans = [];
        this.est = '';
        socket.emit('compAns',quest,ans,this.room.id);
      },
      
    },
    
  };
  </script>
  
<style scoped>
  * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif !important;
  }

  .container {
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
  }

  .game {
      background-color: #1e2736;
      width: 100%;
      height: 100vh;
      display: grid;
      grid-template-rows: repeat(3, 1fr);
      align-items: center;
      justify-items: center;
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

  .player1 .bar .progress {
      width: 60%;
      height: 100%;
      background-color: #d9b444;
      border-radius: 5px;
  }

  .player2 .bar {
      flex-direction: row-reverse;
  }

  .player2 .bar .progress {
      width: 90%;
      height: 100%;
      background-color: #44d953;
      border-radius: 5px;
  }

  .player .name {
      font-size: 18px;
      font-weight: 600;
      color: #fff;
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
      width: 80px;
      height: 80px;
      color: black;
      background-color: #fff;
      border-radius: 50%;
      text-align: center;
  }

  .timer .time {
      font-size: 40px;
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

  .title{
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
    background-image: url(../public/img/card-background.jpg);
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    z-index: 2; 
    transition: all .3s ease-in-out;
  }

  .title-ans{
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
</style>
  