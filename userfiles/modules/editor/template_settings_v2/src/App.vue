
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


        setTimeout(function() {

            liveEdit.moduleHandle.on('targetChange', function (target) {

                console.log('targetChange', target); 
                this.greeting = target;

            });

        }, 3000);

    },
    data() {
        return {
            greeting: 'Hello World!'
        }
    }
}
</script>
