<template>
    <div>
        <h1>Rooms</h1>
        <input type="text" style="border: 1px solid black;" v-model="this.room">
        <button @click="createRoom">CREAR</button>
        <div v-for="room in rooms" :key="room">
          {{ room.name }}
        </div>
    </div>
</template>

<script>
  import { socket } from "@/socket";
  import {useAppStore} from '../stores/app';
  import { watch } from 'vue';
  import { uid } from 'uid';

  export default {
    data() {
      return {
        room: '',
        rooms: []
      };
    },
    mounted() {
      socket.emit('getRooms');
      const store = useAppStore();
      watch(() => store.rooms, newVal => {
        this.rooms = newVal;
      });
    },
    methods: {
      createRoom() {
        socket.emit('createRoom', this.room,uid());
        this.room = '';
        this.rooms = [];
      }
    }
  };
</script>

<style scoped>

</style>