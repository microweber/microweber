<template>

<div class="d-flex gap-2">
    <select class="form-select">
        <option v-for="(fontFamily) in supportedFonts"
                :value="fontFamily">
            {{ fontFamily }}
        </option>
    </select>
    <button type="button" class="btn btn-outline-dark btn-sm" v-on:click="loadMoreFonts()">
        Load more
    </button>
</div>

</template>

<script>

export default {
    methods: {
        loadMoreFonts() {
            mw.top().app.fontManager.manageFonts();
        }
    },
    mounted() {

        setTimeout(() => {

            this.supportedFonts = mw.top().app.fontManager.getFonts();
            this.$forceUpdate();

            mw.top().app.fontManager.subscribe((fonts) => {
                if (fonts) {
                    this.supportedFonts = fonts;
                }
                this.$forceUpdate();
            });

        }, 1000);
    },
    data() {
        return {
            supportedFonts: [],
        }
    }
}
</script>
