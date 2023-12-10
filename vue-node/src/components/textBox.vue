<template>
    <div class="text-box" ref="textBox" @keydown.space="nextText" tabindex="0">
        <img class="dialogBox" src="../../public/img/DialogBoxFaceset.png" alt="DialogBoxFaceset">
        <img class="npcFace" :src="`../../public/npc/face_${npcImage}.png`" alt="">
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
.text-box {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100vh;
}

.text-box .dialogBox {
    width: 70vw;
    z-index: 1;
}

.text-box .npcFace {
    width: 120px;
    position: absolute;
    top: 106px;
    transform: translateX(-50%) translateY(-50%);
    left: 285px;
    z-index: 2;
}

.text-box .npc-DialogBox {
    position: absolute;
    top: 8%;
    left: 28%;
    width: 55vw;
    height: 17vh;
    z-index: 3;
    overflow: hidden;
}

.text-box {
    outline: none;
}
</style>
