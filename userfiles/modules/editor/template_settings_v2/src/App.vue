
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

<script>
export default {
    methods: {
        updateText() {
            this.greeting = 'Hello Microweber! This is the first vue component in live-edit.'
        }
    },
    mounted() {
        console.log('Component mounted.');


        const appInstance = this;

        setTimeout(function() {

            mw.app.editor.liveEdit.moduleHandle.on('targetChange', function (target) {

                console.log('targetChange', target);
                appInstance.greeting = target.innerHTML;

            });

        }, 8000);

    },
    data() {
        return {
            greeting: 'Hello World!'
        }
    }
}
</script>
