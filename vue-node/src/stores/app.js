import { defineStore } from "pinia";

export const useAppStore = defineStore('app', {
  state: () => ({
    questAct: {},
    respAct: {},
    room: {},
    rooms: [],
    user: {},
    isLogged: false,
    turn: true,
    lastRoute: '',
    players: [],
  }),
  persist: {
    paths: ['user', 'isLogged', 'lastRoute', 'players']
  },
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
      return this.usersCon;
    },
    addQuest(quest) {
      this.questAct = quest;
    },
    addResp(resp) {
      this.respAct = resp;
    },
    getQuest() {
      return this.questAct.pregunta;
    },
    setUser(user) {
      this.user = user;
      this.isLogged = true;
    },
    unsetUser() {
      this.user = {};
      this.isLogged = false;
    },
    getToken() {
      return this.user.tokens;
    },
    setTurnOn() {
      this.turn = true;
    },
    setTurnOff() {
      this.turn = false;
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
    },
    getUsername() {
      return this.user.username;
    },
    getSkin() {
      return this.user.skin;
    },
    updateLife(player) {
      for (let i = 0; i < this.room.players.length; i++) {
        const element = this.room.players[i];
        if (player.id == element.id) {
          element.life = player.life;
        }
      }
    },
    gameOver(player) {
      for (let i = 0; i < this.room.players.length; i++) {
        const element = this.room.players[i];
        if (this.user.username == element.name) {
          if (element.life <= 0) {
            return false
          } else {
            return true
          }
        }
      }
    },
    resetRoom() {
      this.room = {};
    },
    setLastRoute(route) {
      this.lastRoute = route;
    },
    getLastRoute() {
      return this.lastRoute;
    },
    getIsLogged() {
      return this.isLogged;
    },
    getId() {
      return this.user.userId;
    },
    addPlayers(players) {
      this.players = players;
    },
    setNewSkin(skin) {
      this.user.skin = skin;
    }
  },
})