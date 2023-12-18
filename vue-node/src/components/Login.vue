<template>
    <div class="login-container">
        <form class="login-form" method="POST">
            <div class="titulo">
                <h2>BattleMath</h2>
            </div>
            <h1 v-if="error" class="error-message">{{ message }}</h1>
            <h1 v-if="success" class="success-message">{{ message }}</h1>
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
            });

        },
        recibirsuccess() {

            socket.on('success', (successMessage) => {
                this.data = successMessage.data;
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
                showCloseButtonOnHover: true,
                hideProgressBar: false,
                closeButton: "button",
                icon: true,
                rtl: false,
            });
        }

    },
    mounted() {
        this.recibirerror();
        this.recibirsuccess();
    },
    watch: {
        success() {
            if (this.success) {
                this.$emit('user', this.data);
                this.toastNotification();
            } else {
                this.toastNotification();
            }
        }
    }
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
    width: 35vw;

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
    position: relative;
    width: calc(100% - 50px);
    padding: 10px;
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
}</style>