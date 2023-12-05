<template>
  <div v-if="players === 0">
    <div>{{ quest.pregunta }}</div>
    <button @click="genQuest()">Generar Pregunta</button>
    <div v-for="(answer, index) in ans" :key="index">
      <button :value="answer.id" @click="compAns(quest.id,answer.id)">{{ answer.resposta }}</button>
    </div>
    <div>{{est}}</div>
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
        est: '',
        players: 0
      };
    },
    
    mounted() {
      const store = useAppStore();
      watch(() => store.questAct, newVal => {
        this.quest = newVal;
      });

      watch(() => store.respAct, ss => {
        this.ans = ss;
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
        socket.emit('genQuest');
      },
      compAns(quest, ans){
        this.ans = [];
        this.est = '';
        socket.emit('compAns',quest,ans);
      },
      
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
  
  