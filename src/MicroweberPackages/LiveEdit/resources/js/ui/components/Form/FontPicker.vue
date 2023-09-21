<template>


<select class="form-select" >
    <option v-for="(fontFamily) in supportedFonts"
            :value="fontFamily">
        {{ fontFamily }}
    </option>
</select>

<button type="button" class="btn btn-outline-dark btn-sm mt-3" v-on:click="loadMoreFonts()">
    Load more
</button>

</template>

<script>

export default {
    methods: {
        loadMoreFonts() {
            mw.top().app.fontManager.manageFonts();
        }
    },
    mounted() {
        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.fontManager.subscribe((fonts) => {
                if (fonts) {
                    this.supportedFonts = fonts;
                }
                this.$forceUpdate();
            });
        });
    },
    data() {
        return {
            supportedFonts: [],
        }
    }
}
</script>
