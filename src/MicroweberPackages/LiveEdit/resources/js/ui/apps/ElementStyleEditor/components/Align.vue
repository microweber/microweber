<template>
    <div class="s-field">
        <label>Text align</label>
        <div class="s-field-content">
            <div class="text-align">
        <span
            class="ta-left"
            :class="{ active: textAlign === 'left' }"
            @click="setTextAlignment('left')"
            data-value="left"
        >
          <span class="mdi mdi-format-align-left"></span>
        </span>
                <span
                    class="ta-center"
                    :class="{ active: textAlign === 'center' }"
                    @click="setTextAlignment('center')"
                    data-value="center"
                >
          <span class="mdi mdi-format-align-center"></span>
        </span>
                <span
                    class="ta-right"
                    :class="{ active: textAlign === 'right' }"
                    @click="setTextAlignment('right')"
                    data-value="right"
                >
          <span class="mdi mdi-format-align-right"></span>
        </span>
                <span
                    class="ta-justify"
                    :class="{ active: textAlign === 'justify' }"
                    @click="setTextAlignment('justify')"
                    data-value="justify"
                >
          <span class="mdi mdi-format-align-justify"></span>
        </span>
            </div>
        </div>
    </div>
</template>

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
