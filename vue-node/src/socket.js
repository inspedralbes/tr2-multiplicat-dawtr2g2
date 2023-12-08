import { io } from "socket.io-client";
import { useAppStore } from '@/stores/app';
import router from './router'


// "undefined" means the URL will be computed from the `window.location` object
const URL = "http://localhost:3000";

export const socket = io(URL);



socket.on("GameStart", () => {
  if (router.currentRoute.value.path == '/esperar') {
    router.push('/partida');
  }else{
    router.push('/lleno');
  }
});

socket.on("roomCreated", (room) => {
  const store = useAppStore();
  store.addRoom(room);
  router.push('/partida');
});

socket.on("joiningGame", (room) => {
  const store = useAppStore();
  store.addRoom(room);
  console.log('Received joiningGame event with room:', room);
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
  if (router.currentRoute.value.path == '/partida') {
    console.log('Received viewQuest event with quest:', quest);
    const store = useAppStore();
    store.addQuest(quest);
  }
});

socket.on("viewResp", (resp) => {
  if (router.currentRoute.value.path == '/partida') {
    console.log('Received viewResp event with resp:', resp);
    const store = useAppStore();
    store.addResp(resp);
  }
});
