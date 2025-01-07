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
    background-color: white;
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
    color: black;
    text-align: center;
    cursor: pointer;
    width: 66px;
    min-height: 66px;

}
html.dark .live-edit-user-bar-nav{
    background-color: rgb(24 36 51 / var(--tw-bg-opacity));
    .live-edit-user-bar-nav-item{
        color: white;
    }
}

.live-edit-user-bar-nav-item-icon svg{
    fill: currentColor;
    width: 22px;
    width: 26px;
    height: auto;
}
.live-edit-user-bar-nav-item-label {
    display: block;
    display: none;
    padding: 5px 0 0;
    font-size: 14px;
}
</style>

<template>

    <div class="sidebar-admin-panel">

        <div class="live-edit-user-bar">
            <div class="live-edit-user-bar-nav">


                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22"><path d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"></path></svg></span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Template settings</Lang>
                    </span>
                </span>

                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 -960 960 960" ><path d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"></path></svg></span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Design</Lang>
                    </span>
                </span>
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon"><svg   xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 96 960 960"><path d="M329.59 801.127h300.82v-50.254H329.59v50.254Zm0-164.871h300.82v-50.255H329.59v50.255Zm-87.025 319.743q-25.788 0-44.176-18.388t-18.388-44.176v-634.87q0-25.788 18.388-44.176t44.176-18.388h337.59l199.844 199.844v497.59q0 25.788-18.388 44.176t-44.176 18.388h-474.87Zm312.462-536.513v-173.23H242.565q-4.616 0-8.462 3.847-3.847 3.846-3.847 8.462v634.87q0 4.616 3.847 8.462 3.846 3.847 8.462 3.847h474.87q4.616 0 8.462-3.847 3.847-3.846 3.847-8.462V419.486H555.027Zm-324.771-173.23v173.23-173.23V905.744 246.256Z"></path></svg></span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Pages</Lang>
                    </span>
                </span>
                <span class="live-edit-user-bar-nav-item">
                    <span class="live-edit-user-bar-nav-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="22px" fill="currentColor"><path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"></path></svg>
                    </span>
                    <span class="live-edit-user-bar-nav-item-label">
                        <Lang>Layers</Lang>
                    </span>

                </span>

            </div>
        </div>
    </div>

</template>
