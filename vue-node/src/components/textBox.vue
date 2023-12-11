<template>
    <div class="text-box" ref="textBox" @keydown.space="nextText" tabindex="0">
        <div class="container">
            <!-- <img class="faceBox" src="../../public/img/FacesetBox.png" alt="DialogBoxFaceset"> -->
            <img class="npcFace" :src="`../../public/npc/face_${npcImage}.png`" alt="">
        </div>
        <div class="npc-DialogBox">
            <p>{{ text[currentIndex] }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        text: {
            type: Array,
            default: () => ['Hello World', 'Hello', 'World'],
            required: true,
        },
        npcImage: {
            type: String,
            default: 'Samurai',
            required: true,
        },
    },
    mounted() {
        this.$refs.textBox.focus();
    },
    data() {
        return {
            currentIndex: 0,
            isModalOpen: true,
        };
    },
    methods: {
        nextText() {
            if (this.isModalOpen) {
                if (this.currentIndex < this.text.length - 1) {
                    this.currentIndex++;
                } else {
                    this.isModalOpen = false;
                }
            }
        },
    },
    watch: {
        text(newVal) {
            this.currentIndex = 0;
            this.isModalOpen = true;
        },
        currentIndex(newVal) {
            if (newVal >= this.text.length) {
                this.isModalOpen = false;
            }
        },
        isModalOpen(newVal) {
            if (!newVal) {
                this.$emit('closeText', newVal);
            }
        },
    },

}
</script>

<style scoped>
p {
    font-size: 24px;
}

.text-box {
    display: flex;
    flex-direction: row;
    outline: none;
}

.container {
    border-width: 10px;
    border-style: solid;
    border-image-source: url('../../public/img/FacesetBox.png');
    border-image-slice: 5;
    border-image-repeat: stretch;
}

.npcFace {
    width: 100px;
    height: 100px;
}

.npc-DialogBox {
    padding-left: 20px;
}
</style>
