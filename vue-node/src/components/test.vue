<template>
    <input type="text" v-model="userName">
    <button @click="addUser()">enviar</button>
</template>
  
<style scoped>
    
</style>
  
<script>
    import { socket } from "@/socket";

  
    export default{
        data() {
            return {
                userName: ''
            };
        },
        methods: {
            addUser(){
                socket.emit('user',this.userName)
                this.userName = '';
            }
        },
        computed(){
            
        },
    }
</script>