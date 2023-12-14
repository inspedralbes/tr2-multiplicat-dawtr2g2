<template>
    <div class="char-select">
        <div class="carousel-container">
            <button class="nes-btn" v-if="!loading" @click.prevent="scroll('left')">
                <img src="../../public/icons/arrow-left.svg" alt="arrow" />
            </button>
            <div class="carousel">
                <div v-for="(character, index) in characters" :key="index" class="character"
                    v-if="index === selectedCharacterIndex" @click="selectCharacter(index)">
                    <img :src="'/characters/' + character.name + '.png'" :alt="character.name" />
                    <p>{{ character.name }}</p>
                </div>
            </div>
            <div class="visible-container">
                <span v-if="loading">Carregant...</span>
                <div v-if="selectedCharacter && !loading" class="centered-character">
                    <div class="faceset-container">
                        <img class="facesetBox" src="/img/FacesetBox.png" alt="">
                        <img class="faceset" :src="'/characters/' + selectedCharacter.name + '_face.png'"
                            :alt="selectedCharacter.name">
                    </div>
                </div>
            </div>
            <button class="nes-btn" v-if="!loading" @click.prevent="scroll('right')">
                <img src="../../public/icons/arrow-right.svg" alt="arrow" />
            </button>
        </div>
    </div>
</template>


<script>
import { socket } from "@/socket";

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
        socket.emit('getSkins');
        socket.on('viewSkins', (skins) => {
            this.characters = skins;
            this.loading = false;
        });
    },
    computed: {
        selectedCharacter() {
            if (this.characters[this.selectedCharacterIndex] != null) {
                const character = this.characters[this.selectedCharacterIndex];
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

<style scoped>
span {
    font-size: 22px;
}

button {
    border-image-repeat: stretch !important;
    background-color: #ffad5d !important;
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

button {
    display: flex;
    justify-content: center;
    align-items: center;
}

button>img {
    width: 50%;
}

.nes-btn:active:not(.is-disabled)::after {
    box-shadow: inset 4px 4px #e46d3a !important;
}

.visible-container {
    margin: 20px;
}

.carousel-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.carousel {
    display: flex;
}

button {
    width: 100px;
    height: 50px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 18px;
    border-image-repeat: stretch !important;
}

.centered-character {
    text-align: center;
    position: relative;
}


.faceset-container {
    position: relative;
    display: inline-block;
}

.facesetBox {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    width: 124px;
}

.faceset {
    position: relative;
    width: 100px;
}
</style>