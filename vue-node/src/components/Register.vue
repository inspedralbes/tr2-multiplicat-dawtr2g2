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
                <div class="username-input">
                    <input type="text" id="username" name="username" v-model="username" required placeholder="Usuari">
                    <span class="user-icon"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="username">Email</label>
                <div class="email-input">
                    <input type="email" id="email" name="email" v-model="email" required placeholder="correu@exemple.com">
                    <span class="search-icon"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <div class="password-input">
                    <input type="password" id="password" name="password" v-model="password" required
                        placeholder="Contrasenya">
                    <span class="lock-icon"></span>
                </div>
            </div>

            <div class="input-group">
                <label for="password_confirmation">Confirma la contrasenya</label>
                <div class="password-input">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        v-model="password_confirmation" required placeholder="Confirmació">
                    <span class="lock-icon"></span>
                </div>
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
        }, validateEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        },
        async registerUser() {
            socket.emit('register', this.username, this.email, this.password, this.password_confirmation, this.skinSelected);
        },
        async registerAndNavigate() {
            if (!this.validateEmail(this.email)) {
                this.error = true;
                this.message = 'Correu electrònic no vàlid';
                return;
            }
            await this.registerUser();
            this.error = false;
        },
        recibirerror() {
            socket.on("error400", (errorMessage) => {
                this.message = errorMessage;
                this.error = true;

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
.password-input {
    position: relative;
}

.lock-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-55%);
    right: 33px;
    width: 25px;
    height: 25px;
    filter: invert(50%);
    background-image: url('https://static.thenounproject.com/png/1507844-200.png');
    background-size: cover;
    background-repeat: no-repeat;
    transition: transform 0.3s ease, right 0.3s ease;

}

.password-input input:focus+.lock-icon {
    transform: translateY(-55%) scale(1.2);
    filter: saturate(0%) brightness(1000%);
    right: 30px;
}

.email-input {
    position: relative;
}

.search-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 33px;
    width: 25px;
    height: 20px;
    filter: invert(50%);
    background-image: url('https://cdn1.iconfinder.com/data/icons/pixel-art-essential/512/Search-512.png');
    background-size: cover;
    background-repeat: no-repeat;
    transition: transform 0.3s ease, right 0.3s ease;
}

.email-input input:focus+.search-icon {
    transform: translateY(-50%) scale(1.2);
    filter: saturate(0%) brightness(1000%);
    right: 30px;
}

.username-input {
    position: relative;
}

.user-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 33px;
    width: 25px;
    height: 25px;
    background-image: url('https://cdn1.iconfinder.com/data/icons/pixel-art-essential/512/User-512.png');
    background-size: cover;
    background-repeat: no-repeat;
    filter: invert(50%);
    transition: transform 0.3s ease, right 0.3s ease;
}

.username-input input:focus+.user-icon {
    transform: translateY(-50%) scale(1.2);
    filter: saturate(0%) brightness(1000%);
    right: 30px;
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
    width: 35vw;
    height: 92vh;

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
    margin-top: 17px;
    margin-left: 25px;
}

.input-group input {
    position: relative;
    width: calc(100% - 50px);
    padding: 10px 39px 10px 10px;
    margin: 5px 25px;
    border: none;
    border-radius: 4px;
    border-bottom: 2px solid black;
    box-sizing: border-box;
    background-color: transparent;
    transition: border-bottom-width 0.2s;

}

.input-group input:focus {
    outline: none;
    border-bottom-width: 3px;
}

.input-group input:focus::placeholder {
    color: transparent;
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
