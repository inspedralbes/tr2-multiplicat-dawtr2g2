<template>
    <div class="preloader" id="preloader">
        <h1>BATTLE MATH</h1>
        <img class="gif" src="../../public/img/moveRight.gif" alt="Loading animation">
    </div>
</template>

<style scoped>
.preloader {
    width: 100%;
    height: 100vh;
    background-color: #171717;
    position: fixed;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    animation: preload 2s ease 1 2s both;
}

.preloader h1 {
    animation: preloadLogo 2s ease 1 0.5s both;
    color: white;
}
.gif{
    width: 50px;
    height: 50px;
    position: absolute;
    top: 55%;
    left: 20%;
    opacity: 0;
    animation: preloadGif 4s ease 1 .5s;
}

/* -------------------------------------------------------------------------- */
/*                                  KEYFRAMES                                 */
/* -------------------------------------------------------------------------- */

@keyframes preload {
    0% {
        opacity: 1;
    }
    99.9% {
        opacity: 0;
    }
    100% {
        display: none;
    }
}

@keyframes preloadLogo {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    25% {
        transform: scale(1);
        opacity: 1;
    }
    75% {
        transform: scale(1);
        opacity: 1;
    }
    99.9% {
        transform: scale(2);
        opacity: 0;
    }
    100% {
        display: none;
    }
}

@keyframes preloadGif {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    25% {
        transform: scale(1);
        opacity: 1;
    }
    75% {
        transform: translateX(80vw);
        opacity: 1;
    }
    99.9% {
        /* transform: translateX(80vw); */
        opacity: 0;
    }
    100% {
        display: none;
    }
}
</style>
