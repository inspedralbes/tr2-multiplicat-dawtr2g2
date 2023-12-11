<script>
import router from '../router';
import { socket } from "@/socket";

export default {
    data() {
        return {
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            message: '',
            error:false
        }
    },
    methods: {
        irARuta() {
            router.push('/game');
        },
        irARutaload() {
            router.push('/loading');
            console.log("entra?")
        },
        async registerUser() {
            socket.emit('register', this.username, this.email, this.password, this.password_confirmation);
        },
        async registerAndNavigate() {
            this.irARutaload();
            await this.registerUser();
            this.irARuta();
        },
        recibirerror() {
            socket.on('error400', (errorMessage) => {
                this.message = errorMessage;
                this.error = true;
                console.log('Mensaje de error recibido:', this.message);
            });
            
        }
    },
    mounted() {
        this.recibirerror();
    }
}
</script>


<template>
    <div class="register-container">
        <form class="register-form">
            <div class="titulo">
                <h2 >BattleMath</h2>
            </div>
            <h1 v-if="error" class="error-message">{{ message }}</h1>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" v-model="username" required>
            </div>
            <div class="input-group">
                <label for="username">Email</label>
                <input type="email" id="email" name="email" v-model="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" v-model="password" required>
            </div>
            <div class="input-group">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    v-model="password_confirmation" required>
            </div>
            <button @click="registerAndNavigate()" type="submit">Register</button>
        </form>
    </div>
</template>

<style scoped>
@import url('https://fonts.cdnfonts.com/css/minecraft-3');

.error-message {
    background-color: rgb(255, 95, 95);
    color: white;
    padding: 10px;
    border-radius: 0 0 5px 5px;
    display: flex;
    flex-direction: column;
    margin: 0;
}

h2 {
    color: white;
    margin-top: 20px;
}

.titulo {
    background-color: #007bff;
    width: 100%;
    height: 65px;
    display: flex;
    justify-content: center;
    align-content: center;
}

.register-container {
    width: 100vw;
    height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Minecraft', sans-serif;

}


.register-form {
    padding: 0 0px 50px 0px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

.register-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.register-group {
    margin-bottom: 15px;

}

.input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    margin-top: 25px;
    margin-left: 25px;

}

.input-group{
    width: 500px;
}

.input-group input {
  width: calc(100% - 50px);
  padding: 10px;
  margin: 5px 25px;
  border-radius: 4px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
    margin-left: 25px;
    margin-right: 25px;

}

button:hover {

    background-color: #0056b3;
}
</style>