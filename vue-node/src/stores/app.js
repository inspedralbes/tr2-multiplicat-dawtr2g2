import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    users: [],
    usersCon: 0,
    questAct: {},
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
      console.log('Adding quest to store:', quest);
      this.questAct = quest;
      console.log('QuestAct: ', this.questAct);
    },
    getQuest(){
      console.log(this.questAct);
      return this.questAct.pregunta;
    },
  },
})