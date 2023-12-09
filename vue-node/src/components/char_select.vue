<template>
    <div>
        <div class="carousel-container">
            <button @click="scroll('left')">←</button>
            <div class="carousel">
                <div v-for="(character, index) in characters" :key="index" class="character"
                    v-if="index === selectedCharacterIndex" @click="selectCharacter(index)">
                    <img :src="'/characters/' + character.name + '.png'" :alt="character.name" />
                    <p>{{ character.name }}</p>
                </div>
            </div>
            <span v-if="loading" class="loader"></span>
            <div v-if="selectedCharacter && !loading" class="centered-character">
                <img :src="'/characters/' + selectedCharacter.name + '_face.png'" :alt="selectedCharacter.name">
            </div>
            <button @click="scroll('right')">→</button>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            characters: [],
            selectedCharacterIndex: 0,
            translateValue: 0,
            loading: true
        };
    },
    mounted() {

        axios.get('http://localhost:8000/api/skins')
            .then(response => {
                this.characters = response.data;
            })
            .finally(() => {
                this.loading = false;
            })
            .catch(error => {
                console.error('Error al obtener los datos de la API', error);
            });

    },
    computed: {
        selectedCharacter() {
            if (this.characters[this.selectedCharacterIndex] != null) {
                const character = this.characters[this.selectedCharacterIndex].name;
                this.$emit('selectedCharacter', character);
                return this.characters[this.selectedCharacterIndex];
            }
        }
    },
    methods: {
        selectCharacter(index) {
            this.selectedCharacterIndex = index;
        },
        scroll(direction) {
            if (direction === 'left') {
                if (this.selectedCharacterIndex > 0) {
                    this.selectedCharacterIndex--;
                } else {
                    this.selectedCharacterIndex = this.characters.length - 1;
                }
            } else {
                // Si llega al último personaje, vuelve al primero
                if (this.selectedCharacterIndex < this.characters.length - 1) {
                    this.selectedCharacterIndex++;
                } else {
                    this.selectedCharacterIndex = 0;
                }
            }
        }
    }
};
</script>

<style>
/* Estilos para el carrusel y los personajes */

.carousel-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.carousel {
    display: flex;
}

button {
    padding: 8px 12px;
    background-color: lightgray;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

.centered-character {
    text-align: center;
}

.centered-character img {
    width: 100px;
}

.loader {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    background: linear-gradient(0deg, rgba(255, 61, 0, 0.2) 33%, #ff3d00 100%);
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

.loader::after {
    content: '';
    box-sizing: border-box;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #263238;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg)
    }

    100% {
        transform: rotate(360deg)
    }
}</style>