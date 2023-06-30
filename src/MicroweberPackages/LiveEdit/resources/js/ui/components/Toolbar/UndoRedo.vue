<template>
 
 
</template>


<script>
export default {
    methods: {
        undo() {
            mw.app.state.undo()
        },
        redo() {
            mw.app.state.redo()
        },
        setButtons() {
            this.undoIsDisabled = !mw.app.state.hasPrev;
            this.redoIsDisabled =   !mw.app.state.hasNext;
        }
    },
    data() {
        return {
            undoIsDisabled: true,
            redoIsDisabled: true,
        };
    },
    mounted() {
        mw.app.on('ready', () => {
            mw.app.state.on('record', () => this.setButtons())
        })
    },
 
}
</script>
