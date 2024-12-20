<script>





export default {

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
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;

}
.live-edit-user-bar-nav{
    border-radius: 0.25rem;
    background-color: rgb(24 36 51 / var(--tw-bg-opacity)) !important;
    padding: 5px;
}
.live-edit-user-bar-nav-item:hover{
    background-color: rgba(255,255,255,.1);
}
.live-edit-user-bar-nav-item{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 5px;
    border-radius: 0.25rem;
    color: white;
    text-align: center;
    cursor: pointer;
    width: 66px;
    height: 66px;
}

.live-edit-user-bar-nav-item-icon svg{
    fill: white;
    width: 22px;
}
.live-edit-user-bar-nav-item-label {
    display: block;
    padding: 5px 0 0;
    font-size: 14px;
}
</style>

<template>

    <div class="sidebar-admin-panel">

        <div class="live-edit-user-bar">
            <div class="live-edit-user-bar-nav">
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="22px" fill="currentColor"><path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"></path></svg>
                    </span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Layers</Lang>
                    </span>

                </span>
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon"><svg   xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 96 960 960"><path d="M329.59 801.127h300.82v-50.254H329.59v50.254Zm0-164.871h300.82v-50.255H329.59v50.255Zm-87.025 319.743q-25.788 0-44.176-18.388t-18.388-44.176v-634.87q0-25.788 18.388-44.176t44.176-18.388h337.59l199.844 199.844v497.59q0 25.788-18.388 44.176t-44.176 18.388h-474.87Zm312.462-536.513v-173.23H242.565q-4.616 0-8.462 3.847-3.847 3.846-3.847 8.462v634.87q0 4.616 3.847 8.462 3.846 3.847 8.462 3.847h474.87q4.616 0 8.462-3.847 3.847-3.846 3.847-8.462V419.486H555.027Zm-324.771-173.23v173.23-173.23V905.744 246.256Z"></path></svg></span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Pages</Lang>
                    </span>

                </span>
            </div>
        </div>
    </div>

</template>
