<template>
    <!--
      <div>
        <h1>Estas</h1>
        <canvas ref="miCanvas"></canvas>
      </div>
    -->
    <div >{{ quest.pregunta }}</div>
    <button @click="genQuest()">Generar Pregunta</button>
    <div v-for="(answer, index) in ans" :key="index">
      <button :value="answer.id" @click="compAns(answer.id)">{{ answer.resposta }}</button>
    </div>
  </template>
  
  <script>
  import { socket } from "@/socket";
  import {useAppStore} from '../stores/app';
  import { watch } from 'vue';


  export default {
    
    data() {
      return {
        canvas: null,
        ctx: null,
        pelotas: [],
        quest: '',
        ans: [],
      };
    },
    mounted() {
      /*this.canvas = this.$refs.miCanvas;
      this.ctx = this.canvas.getContext('2d');
      this.dibujarCirculos();*/
      
      const store = useAppStore();
      watch(() => store.questAct, newVal => {
        this.quest = newVal;
      });

      watch(() => store.respAct, ss => {
        this.ans = ss;
      });
    },
    methods: {
      genQuest(){
        socket.emit('genQuest');
      },
      compAns(ans){
        this.ans = [];
        socket.emit('compAns',ans);
      },
      dibujarCirculos() {
        const pelotaInferiorIzquierda = {
          x: 40,
          y: this.canvas.height - 40,
          radio: 20,
          color: "#00F",
        };
  
        const pelotaSuperiorDerecha = {
          x: this.canvas.width - 40,
          y: 40,
          radio: 20,
          color: "#F00",
        };
  
        this.pelotas.push(pelotaInferiorIzquierda, pelotaSuperiorDerecha);
  
        this.dibujarPelota(pelotaInferiorIzquierda);
        this.dibujarPelota(pelotaSuperiorDerecha);
      },
  
      dibujarPelota(pelota) {
        this.ctx.beginPath();
        this.ctx.arc(pelota.x, pelota.y, pelota.radio, 0, 2 * Math.PI);
        this.ctx.fillStyle = pelota.color;
        this.ctx.fill();
        this.ctx.closePath();
      }
      
    },

  };

  </script>
  
  <style scoped>
  canvas {
    border: 1px solid white;
    width: 100%;
    height: 100%;
  }
  </style>
  
  