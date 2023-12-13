<template>
    <div class="login-container">
        <form class="login-form" method="POST">
            <div class="titulo">
                <h2>BattleMath</h2>
            </div>
            <h1 v-if="error" class="error-message">{{ message }}</h1>
            <h1 v-if="success" class="success-message">{{ message }}</h1>
            <div class="input-group">
                <label for="email">E-mail</label>
                <input class="nes-input" type="email" id="email" name="email" v-model="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contrasenya</label>
                <input class="nes-input" type="password" id="password" name="password" v-model="password" required>
            </div>
            <button class="nes-btn" @click="loginAndNavigate" type="button">Login</button>
        </form>
    </div>
</template>

<script>
import router from '../router';
import { socket } from "@/socket";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    data() {
        return {
            email: '',
            password: '',
            error: false,
            success: false,
            message: '',
            data: '',
        };
    },
    methods: {
        irARuta() {
            router.push('/game');
        },
        async loginUser() {
            socket.emit('login', this.email, this.password);
        },
        async loginAndNavigate() {
            await this.loginUser();
            this.error = false;

        },
        recibirerror() {
            socket.on('error400', (errorMessage) => {
                this.message = errorMessage;
                this.error = true;
                console.log('Mensaje de error recibido:', this.message);

            });

        },
        recibirsucess() {

            socket.on('success', (successMessage) => {
                this.data = successMessage.user;
                this.message = successMessage.success;
                this.success = true;
                this.toastNotification();
            });
        },
        toastNotification() {
            toast.success(this.message, {
                position: "top-right",
                timeout: 2000,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: true,
                hideProgressBar: false,
                closeButton: "button",
                icon: true,
                rtl: false,
            });
        },
    },
    mounted() {
        this.recibirerror();
        this.recibirsucess();
    },
    watch: {
        success() {
            if (this.success) {
                this.$emit('user', this.data);
            }
        }
    }
};
</script>
<style scoped>
h2 {
    color: black;
    margin-top: 20px;
}

.error-message {
    background-color: rgb(255, 95, 95);
    color: white;
    font-size: 16px;
    padding: 15px 5px 15px 5px;
    border-radius: 0 0 5px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0;
}

.success-message {
    background-color: rgb(95, 255, 95);
    color: white;
    font-size: 16px;
    padding: 15px 5px 15px 5px;
    border-radius: 0 0 5px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0;

}

.titulo {
    background-color: #ffad5d;
    width: 100%;
    height: 65px;
    display: flex;
    justify-content: center;
    align-content: center;
    margin-top: 20px;
}

.login-container {
    width: 25vw;

}

button {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
    margin-top: 25px;
}

button::after {
    box-shadow: inset -4px -4px #e46d3a !important;
}

button:hover {
    background-color: #ec9e50 !important;
}

button:hover::after {
    box-shadow: inset -6px -6px #e46d3a !important;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a !important;
}

.login-form {
    padding: 0 0px 25px 0px;
    display: flex;
    flex-direction: column;
    margin: 20px 30px 0px 30px;
}

.login-form h2 {
    text-align: center;
    margin-bottom: 20px;
}


.input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    margin-top: 25px;
    margin-left: 25px;

}

.input-group input {
    width: calc(100% - 50px);
    padding: 10px;
    margin: 5px 25px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;

}
</style>