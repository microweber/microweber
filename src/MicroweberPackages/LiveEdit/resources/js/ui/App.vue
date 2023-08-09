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

    }
}
</script>
