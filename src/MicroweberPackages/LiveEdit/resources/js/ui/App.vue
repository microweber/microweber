<template>

    <div>
        <Toolbar></Toolbar>
        <LeftSidebar></LeftSidebar>
        <RightSidebar></RightSidebar>
        <ListLayouts></ListLayouts>
        <ListModules></ListModules>
       <ElementSettingsModal></ElementSettingsModal>
<!--       <ModalsContainer ></ModalsContainer >-->

    </div>

</template>



<script>
import { ModalsContainer } from 'vue-final-modal';
import Toolbar from "./components/Toolbar/Toolbar.vue";
import LeftSidebar from "./components/LeftSidebar/LeftSidebar.vue";
import RightSidebar from "./components/RightSidebar/RightSidebar.vue";
import ListLayouts from "./components/Layouts/ListLayouts.vue";
import ListModules from "./components/Modules/ListModules.vue";
import ElementSettingsModal from "./components/ElementSettings/ElementSettingsModal.vue";

export default {
    components: {
        ElementSettingsModal,
        ModalsContainer,
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
