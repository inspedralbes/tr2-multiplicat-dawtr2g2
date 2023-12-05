<template>
    <div>
        <h1>Rooms</h1>
        <input type="text" style="border: 1px solid black;" v-model="this.room">
        <button @click="createRoom">CREAR</button>
    </div>
</template>

<script>
  import { socket } from "@/socket";
  import {useAppStore} from '../stores/app';
  import { uid } from 'uid';

  export default {
    data() {
      return {
        room: ''
      };
    },
    methods: {
      createRoom() {
        socket.emit('createRoom', this.room,uid());
        this.room = '';
      }
    }
  };
</script>

<style scoped>

</style>