<script >
export default {
    mounted (){
        const node = this.$refs.lang;
        if(node) {
            node.textContent = mw.lang( this.$slots.default()[0].children );
        }
    }
}
</script>

<template>

    <span ref="lang"><slot></slot></span>

</template>

