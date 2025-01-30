<template>
    <div class="form-control-live-edit-label-wrapper">
        <label class="live-edit-label">Align</label>
        <div class="s-field-content">
            <div class="text-align">
        <span
            class="ta-left"
            :class="{ active: textAlign === 'left' }"
            @click="setTextAlignment('left')"
            data-value="left"
        >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3,3H21V5H3V3M3,7H15V9H3V7M3,11H21V13H3V11M3,15H15V17H3V15M3,19H21V21H3V19Z" /></svg>
        </span>
                <span
                    class="ta-center"
                    :class="{ active: textAlign === 'center' }"
                    @click="setTextAlignment('center')"
                    data-value="center"
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3,3H21V5H3V3M7,7H17V9H7V7M3,11H21V13H3V11M7,15H17V17H7V15M3,19H21V21H3V19Z" /></svg>
        </span>
                <span
                    class="ta-right"
                    :class="{ active: textAlign === 'right' }"
                    @click="setTextAlignment('right')"
                    data-value="right"
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3,3H21V5H3V3M9,7H21V9H9V7M3,11H21V13H3V11M9,15H21V17H9V15M3,19H21V21H3V19Z" /></svg>
        </span>
                <span
                    class="ta-justify"
                    :class="{ active: textAlign === 'justify' }"
                    @click="setTextAlignment('justify')"
                    data-value="justify"
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3,3H21V5H3V3M3,7H21V9H3V7M3,11H21V13H3V11M3,15H21V17H3V15M3,19H21V21H3V19Z" /></svg>
        </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    svg {
        width: 20px;
        margin: auto;
        fill: currentColor;
    }
</style>

<script>
export default {
    props: {
        textAlign: String, // Pass the text alignment property as a prop
    },
    methods: {
        setTextAlignment(alignment) {
            this.$emit('update:textAlign', alignment);
        },
    },
};
</script>
