import { io } from "socket.io-client";
import { useAppStore } from '@/stores/app';
import router from './router'


// "undefined" means the URL will be computed from the `window.location` object
const URL = "http://localhost:3000";

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

socket.on("timer", (timer) => {
  const store = useAppStore();
  store.canviarTimer(timer);
});