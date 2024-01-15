<template>
    <div :class="['text-box']" ref="textBox" @keydown.space="nextText" tabindex="0">
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
        isMobileDevice() {
            const userAgent = navigator.userAgent;
            const mobileKeywords = [
                'Android',
                'webOS',
                'iPhone',
                'iPad',
                'iPod',
                'BlackBerry',
                'Windows Phone'
            ];

            return mobileKeywords.some(keyword => userAgent.includes(keyword));
        }
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

.npcFace {
    width: 100px;
    height: 100px;
}

.npc-DialogBox {
    padding-left: 20px;
}
</style>
