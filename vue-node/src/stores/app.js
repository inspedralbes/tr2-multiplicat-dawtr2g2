import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    questAct: {},
    respAct: {},
    room: {},
    rooms: [],
    user: {},
    turn: true,
  }),
  actions: {
    addRoom(room) {
      this.room = room;
    },
    addRooms(rooms) {
      this.rooms = rooms;
    },
    getRoom() {
      return this.room.players;
    },
    getLong() {
      console.log(this.usersCon);
      return this.usersCon;
    },
    addQuest(quest) {
      this.questAct = quest;
    },
    addResp(resp) {
      this.respAct = resp;
    },
    getQuest() {
      console.log(this.questAct);
      return this.questAct.pregunta;
    },
    setUser(user) {
      this.user = user;
    },
    setTurnOn() {
      this.turn = true;
      console.log(this.turn);
    },
    setTurnOff() {
      this.turn = false;
      console.log(this.turn);
    },
    getTurn() {
      return this.turn;
    },
    canviarTurn() {
      if (this.turn === true) {
        this.turn = !this.turn;
      } else {
        this.turn = true
      }
    },
    settimeUp() {
      this.room.timeUp = true;
    },
    settimeOff() {
      this.room.timeUp = false;
    },
    getTimer() {
      return this.room.timer;
    },
    getTimeUp() {
      return this.room.timeUp;
    },
    canviarTimer(secs) {
      this.room.timer = secs;
      // console.log(secs);
    },
    getUsername() {
      return this.user.username;
    },
    getSkin(){
      return this.user.skin;
    },
    updateLife(player){
      console.log(player);
      for (let i = 0; i < this.room.players.length; i++) {
        const element = this.room.players[i];
        if (player.id == element.id) {
          element.life = player.life;
        }
      }
      console.log(this.room.players);
    } 
  },
})