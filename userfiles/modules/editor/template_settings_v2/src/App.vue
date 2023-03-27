
<template>
    <div>
        <p class="greeting">{{ greeting }}</p>
        <button type="button" v-on:click="updateText">Update Text</button>
        <button type="button" v-on:click="updateFontSize">Make Red Color</button>
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
        },
        updateFontSize()
        {
            mw.app.editor.richTextEditorAPI.fontSize(74);
        }
    },
    mounted() {
        console.log('Component mounted.');


        const appInstance = this;

        setTimeout(function() {

            // mw.app.editor.liveEdit.elementHandle.on('targetChange', function (target) {
            //     console.log('targetChange', target);
            //     appInstance.greeting = target.innerHTML;
            // });

        }, 7000);

    },
    data() {
        return {
            greeting: 'Hello World!'
        }
    }
}
</script>
