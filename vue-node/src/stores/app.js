import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    questAct: {},
    respAct: {},
    room: {},
    rooms: [],
    user:{}
  }),
  actions: {
    addRoom(room){
      this.room = room;
    },
    addRooms(rooms){
      this.rooms = rooms;
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
    setUser(user){
      this.user = user;
    }
  },
})