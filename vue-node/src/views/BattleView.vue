<script setup>
// import TheWelcome from "../components/TheWelcome.vue";
</script>


<template>
    <div class="container">
        <div class="game">
            <header>
                <div class="player1">
                    
                </div>
                <div class="timer"></div>
                <div class="player2"></div>
            </header>

            <main>
                <div class="character1"></div>
                <div class="question"></div>
                <div class="character2"></div>
            </main>

            <footer class="cards">
                <div class="card"></div>
                <div class="card"></div>
                <div class="card"></div>
                <div class="card"></div>
            </footer>

        </div>
    </div>
</template>

<style>
.container {
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.game {
    width: 100%;
    height: 100vh;
    display: grid;
    align-items: center;
    justify-items: center;
}
</style>
