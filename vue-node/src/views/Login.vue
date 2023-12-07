<script>
import router from '../router';

export default {
    methods: {
        irARuta() {
            router.push('/game');
        }
    }
}
</script>

<template>
    <div class="register-container">
        <form class="register-form">
            <div class="titulo">
                <h2>BattleMath</h2>
            </div>
            <div class="input-group">
                <label for="username">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</template>

<style scoped>
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

.input-group input {
    width: 89%;
    padding: 10px;
    margin-left: 25px;
    padding-right: 229px;
    border-radius: 4px;
    border: 1px solid #ccc;

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