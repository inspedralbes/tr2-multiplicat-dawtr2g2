<template>
    <div class="register-container">
        <form class="register-form" method="POST">
            <div class="titulo">
                <h2>BattleMath</h2>
            </div>
            <h1 v-if="error" class="error-message">{{ message }}</h1>
            <h1 v-if="success" class="success-message">{{ message }}</h1>
            <div class="input-group">
                <label for="username">Nom d'usuari</label>
                <input type="text" id="username" name="username" v-model="username" required>
            </div>
            <div class="input-group">
                <label for="username">Email</label>
                <input type="email" id="email" name="email" v-model="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contrasenya</label>
                <input type="password" id="password" name="password" v-model="password" required>
            </div>
            <div class="input-group">
                <label for="password_confirmation">Confirma la contrasenya</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    v-model="password_confirmation" required>
            </div>
            <div class="input-group">
                <label for="skin">Selecciona el teu personatge</label>
                <char_select @selectedCharacter="selectSkin"></char_select>
            </div>
            <button class="nes-btn" @click="registerAndNavigate" type="button">Registra't</button>
        </form>
    </div>
</template>

<script>
import router from "../router";
import { socket } from "@/socket";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import char_select from '@/components/char_select.vue';

export default {
    components: {
        char_select
    },
    data() {
        return {
            user: {},
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            message: '',
            skinSelected: '',
            error: false,
            success: false
        }
    },
    methods: {
        irARutaload() {
            router.push('/loading');
        },
        async registerUser() {
            socket.emit('register', this.username, this.email, this.password, this.password_confirmation, this.skinSelected);
        },
        async registerAndNavigate() {
            await this.registerUser();
            this.error = false;
        },
        recibirerror() {
            socket.on("error400", (errorMessage) => {
                this.message = errorMessage;
                this.error = true;
                console.log('Mensaje de error recibido:', this.message);

            });
        },
        recibirsuccess() {
            socket.on("success", (successMessage) => {
                this.message = successMessage.success;
                this.success = true;
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
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: "button",
                icon: true,
                rtl: false,
            });
        },
        selectSkin(skin) {
            this.skinSelected = skin;
        }
    },
    mounted() {
        this.recibirerror();
        this.recibirsuccess();
    },
    watch: {
        success() {
            if (this.success) {
                this.user = {
                    username: this.username,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    skin_id: this.skinSelected
                }
                this.$emit('user', this.user);
                this.toastNotification();
            }
        },
    },
};
</script>


<style scoped>
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

h2 {
    color: black;
    margin-top: 20px;
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

.register-container {
    width: 25vw;

}

.register-form {
    padding: 0 0px 25px 0px;
    display: flex;
    flex-direction: column;
    margin: 20px 30px 0px 30px;
}

.register-form h2 {
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
</style>
