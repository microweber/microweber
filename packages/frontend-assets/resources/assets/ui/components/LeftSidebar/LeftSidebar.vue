<script>



export default {
    components: { },
    data() {

    }
}
</script>

<style scoped>
.sidebar-admin-panel{
    position: fixed;
    top: var(--toolbar-height);
    left: 0;
    z-index: 10;
    height: calc(100vh - var(--toolbar-height));
}
.live-edit-user-bar{
    position: absolute;
    top: 50%;
    left: 20px;
    transform: translateY(-50%);

}
.live-edit-user-bar-nav{
    border-radius: 0.25rem;
    background-color: rgb(24 36 51 / var(--tw-bg-opacity)) !important;
    padding: 5px;
}
.live-edit-user-bar-nav-item{
    display: block;
    padding: 5px;
    border-radius: 0.25rem;
    color: white;
    text-align: center;
}
.live-edit-user-bar-nav-item svg{
    fill: white;
}
</style>

<template>

    <div class="sidebar-admin-panel">

        <div class="live-edit-user-bar">
            <div class="live-edit-user-bar-nav">
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor"><path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"></path></svg>
                    </span>
                    <span class="live-edit-user-bar-nav-item-label">
                        Layers
                    </span>

                </span>
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor"><path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"></path></svg>
                    </span>
                    <span class="live-edit-user-bar-nav-item-label">
                        Layers
                    </span>

                </span>
            </div>
        </div>
    </div>

</template>
