
<script >

 import { useSlots } from 'vue';

export default {
    components: {   },
    mounted (){
        const node = this.$refs.lang;
        const slots = useSlots();
        if(node) {
            node.textContent = mw.lang( this.$slots.default()[0].children );
        }
    }
}
</script>


<template>

    <div ref="lang"><slot></slot></div>

</template>

