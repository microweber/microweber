<script>
export default {
    methods: {
        updateText() {
            this.greeting = 'Hello Microweber! This is the first vue component in live-edit.'
        }
    },
    data() {
        return {
            greeting: 'Hello World!'
        }
    }
}
</script>

<template>
    <div>
        <p class="greeting">{{ greeting }}</p>
        <button type="button" v-on:click="updateText">Update Text</button>
    </div>
</template>

<style>
.greeting {
    color: red;
}
</style>
