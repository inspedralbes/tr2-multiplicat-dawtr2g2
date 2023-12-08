<template>
    <div>
        <h1>Selecciona un personaje:</h1>
        <div class="carousel-container">
            <button @click="scroll('left')">←</button>
            <div class="carousel">
                <div v-for="(character, index) in characters" :key="index" class="character"
                    v-if="index === selectedCharacterIndex" @click="selectCharacter(index)">
                    <img :src="'/characters/' + character.name + '.png'" :alt="character.name" />
                    <p>{{ character.name }}</p>
                </div>
            </div>
            <div v-if="selectedCharacter" class="centered-character">
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
            translateValue: 0
        };
    },
    mounted() {
        axios.get('http://localhost:8000/api/skins')
            .then(response => {
                this.characters = response.data;
            })
            .catch(error => {
                console.error('Error al obtener los datos de la API', error);
            });
    },
    computed: {
        selectedCharacter() {
            return this.characters[this.selectedCharacterIndex];
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
</style>