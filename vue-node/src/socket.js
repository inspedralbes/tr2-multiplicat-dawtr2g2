import { io } from "socket.io-client";
import { useAppStore } from '@/stores/app';
import router from './router'


// "undefined" means the URL will be computed from the `window.location` object
const URL = "http://localhost:3817";

export const socket = io(URL);


socket.on("roomCreated", (room) => {
  const store = useAppStore();
  store.addRoom(room);
  store.setTurnOn();
  router.push('/partida');
});

socket.on("joiningGame", (room) => {
  const store = useAppStore();
  store.addRoom(room);
  store.setTurnOff();
  router.push('/partida');
});

socket.on("playerJoined", (room) => {
  const store = useAppStore();
  store.addRoom(room);
});

socket.on("viewRooms", (rooms) => {
  const store = useAppStore();
  store.addRooms(rooms);
});

socket.on("viewQuest", (quest) => {
  const store = useAppStore();
  store.addQuest(quest);
  store.canviarTurn();
});

socket.on("viewResp", (resp) => {
  const store = useAppStore();
  store.addResp(resp);
});

socket.on("loginParameters", (user) => {
  const store = useAppStore();
  store.setUser(user);
});

socket.on("logoutEliminarInfo", () => {
  const store = useAppStore();
  store.unsetUser();
  
});

socket.on("timer", (timer) => {
  const store = useAppStore();
  store.canviarTimer(timer);
});

socket.on("timeUp", () => {
  const store = useAppStore();
  store.settimeUp();
  store.questAct = {};
  store.respAct = {};
  console.log(store.respAct);
});

socket.on("life",(player) =>{
  const store = useAppStore();
  store.updateLife(player);
});

socket.on("startTimer", () => {
  const store = useAppStore();
  store.settimeOff();
  store.canviarTurn();
});

socket.on("gameOver",(player) =>{
  const store = useAppStore();
  router.push('/endGame');
  store.gameOver(player);
});

socket.on('disconnectRoom', () => {
  router.push('/rooms');
  const store = useAppStore();
  store.resetRoom();
});
socket.on("viewPlayers", (players) => {
  const store = useAppStore();
  store.addPlayers(players);
});