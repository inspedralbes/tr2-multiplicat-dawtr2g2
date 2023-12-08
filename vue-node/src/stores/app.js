import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    users: [],
    usersCon: 0,
    questAct: {},
    respAct: {},
    user:{}
  }),
  actions: {
    addUser(users,long) {
      this.users = users;
      this.usersCon = long;
      console.log(users);
    },
    getUsers(){
      console.log(this.users);
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