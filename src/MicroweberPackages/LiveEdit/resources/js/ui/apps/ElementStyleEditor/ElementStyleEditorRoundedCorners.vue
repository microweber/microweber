<template>
    <div class="d-flex flex-column gap-3">

        <BorderRadius v-model="borderRadius"></BorderRadius>
    </div>
</template>

<script>
import BorderRadius from "./components/BorderRadius.vue";

export default {
    components: {BorderRadius},

    data() {
        return {
            showBorder: false,
            'borderRadius': {
                borderTopLeftRadius: '',
                borderTopRightRadius: '',
                borderBottomLeftRadius: '',
                borderBottomRightRadius: '',
            },


        };
    },
    watch: {

    },
};
</script>


