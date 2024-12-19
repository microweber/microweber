
<script>

 import { useSlots } from 'vue';

export default {
    components: {   },
    data() {

    },
    mounted (){


        const slots = useSlots();
        console.log(this.$slots.default[0] )
        console.log(slots.default()[0].text )
    }
}
</script>

<template>

    <span class="mw-lang" ref="lang"><slot></slot></span>

</template>
