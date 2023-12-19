<template>
    <div>
        <div class="win">
            <h1>VICTORIA</h1>
            <img src="/characters/grayNinja_fight.png" alt="">
            <button class="nes-btn">Tornar</button>
            <img class="confetti" src="/img/confetti-gif-8.gif" alt="">
        </div>

        <div class="lose">
            <h1>DERROTA</h1>
            <img src="/characters/grayNinja_fight.png" alt="">
            <button class="nes-btn">Tornar</button>
            <img class="lluvia" src="/img/lluvia.gif" alt="">
        </div>

    </div>
</template>


<style scoped>
.win, .lose {
    background-color: #1e2736;
    width: 100%;
    height: 100vh;
    display: grid;
    grid-template-rows: repeat(3, 1fr);
    align-items: center;
    justify-items: center;
    position: relative;
}

h1 {
    color: #fff;
    font-size: 5rem;
}

img {
    width: 15%;
    height: auto;
}

button {
    border-image-repeat: stretch;
    background-color: #ffad5d;
}

button::after {
    box-shadow: inset -4px -4px #e46d3a;
}

button:hover {
    background-color: #ec9e50;
}

button:hover::after {
    box-shadow: inset -6px -6px #e46d3a;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a;
}

.confetti {
    width: auto;
    height: 70vh;
    position: absolute;
    top: 0;
    z-index: 1;
    object-fit: cover;
}

.lluvia {
    width: 40%;
    height: 70vh;
    position: absolute;
    top: 0;
    z-index: 1;
    object-fit: cover;
}
</style>