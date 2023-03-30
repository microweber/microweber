<template>
    <h1> Hello, Vuejs with Laravel </h1>
</template>
<script>


export default {
    setup() {


     mw.app.canvas.on('liveEditCanvasLoaded', () => {
            new EditorComponent();
            liveEditComponent();
            const editor = {
                handle: {
                    module:  new ModuleHandleAdapter(),
                    element: new ElementHandleAdapter(),
                },
            };
            mw.app.register('editor', editor);

            mw.app.dispatch('ready');
        });
        
 


    }
}
</script>
