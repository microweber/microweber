<template>

    <div>
        <Toolbar></Toolbar>
        <LeftSidebar></LeftSidebar>
        <RightSidebar></RightSidebar>
        <div id="live-edit-frame-holder"></div>
    </div>

</template>

<style>
#live-edit-frame-holder {
    border: 3px solid #0a53be;
}
</style>

<script setup>
import Toolbar from "./components/Toolbar/Toolbar.vue";
import LeftSidebar from "./components/LeftSidebar/LeftSidebar.vue";
import RightSidebar from "./components/RightSidebar/RightSidebar.vue";
</script>
