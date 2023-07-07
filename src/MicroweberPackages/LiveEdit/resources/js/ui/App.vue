<template>

    <div>
        <Toolbar></Toolbar>
        <LeftSidebar></LeftSidebar>
        <RightSidebar></RightSidebar>
        <ListLayouts></ListLayouts>
        <ListModules></ListModules>

    </div>

</template>

<style>
@import "//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";

#live-edit-frame-holder {

}
</style>


<script>

import Toolbar from "./components/Toolbar/Toolbar.vue";
import LeftSidebar from "./components/LeftSidebar/LeftSidebar.vue";
import RightSidebar from "./components/RightSidebar/RightSidebar.vue";
import ListLayouts from "./components/Layouts/ListLayouts.vue";
import ListModules from "./components/Modules/ListModules.vue";

export default {
    components: {
        Toolbar,
        LeftSidebar,
        RightSidebar,
        ListLayouts,
        ListModules
    },

    data() {
        return {

        }
    },
    mounted() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
}
</script>
