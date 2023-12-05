import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    questAct: {},
    respAct: {}, 
    room: {}
  }),
  actions: {
    addRoom(room){
      this.room = room;
    },
    getRoom(){
      return this.room.players;
    },
    getLong(){
      console.log(this.usersCon);
      return this.usersCon;
    },
    addQuest(quest){
      this.questAct = quest;
    },
    addResp(resp){
      this.respAct = resp;
    },
    getQuest(){
      console.log(this.questAct);
      return this.questAct.pregunta;
    },
  },
})