
<template>
    <div id="toolbar" class="shadow-sm">
        <div class="toolbar-nav toolbar-nav-hover">
            <a href="./" class="mw-le-btn mw-le-btn-icon mw-le-btn-primary2">
                <svg viewBox="0 0 40 40">
                    <path d="M20 27.3l2.1-2.1-3.7-3.7h9.1v-3h-9.1l3.7-3.7-2.1-2.1-7.3 7.3 7.3 7.3zM20 40c-2.73 0-5.32-.52-7.75-1.58-2.43-1.05-4.56-2.48-6.38-4.3s-3.25-3.94-4.3-6.38S0 22.73 0 20c0-2.77.53-5.37 1.57-7.8s2.48-4.55 4.3-6.35 3.94-3.22 6.38-4.28S17.27 0 20 0c2.77 0 5.37.53 7.8 1.57s4.55 2.48 6.35 4.28c1.8 1.8 3.23 3.92 4.28 6.35C39.48 14.63 40 17.23 40 20c0 2.73-.52 5.32-1.58 7.75-1.05 2.43-2.48 4.56-4.28 6.38-1.8 1.82-3.92 3.25-6.35 4.3C25.37 39.48 22.77 40 20 40zm0-3c4.73 0 8.75-1.66 12.05-4.97C35.35 28.71 37 24.7 37 20c0-4.73-1.65-8.75-4.95-12.05C28.75 4.65 24.73 3 20 3c-4.7 0-8.71 1.65-12.02 4.95S3 15.27 3 20c0 4.7 1.66 8.71 4.98 12.03C11.29 35.34 15.3 37 20 37z"></path>
                </svg>
            </a>
        </div>

        <ResolutionSwitch></ResolutionSwitch>
        <Editor></Editor>

        <div class="toolbar-col">
            <div class="toolbar-col-container">
                <div class="btn-group">
                    <UndoRedo></UndoRedo>
                    <SaveButton></SaveButton>
                    <button class="btn btn-icon" @click="pagePreviewToggle()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20"><path
                            d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/></svg>
                    </button>
                </div>
            </div>

            <div id="user-menu-wrapper">
                <span class="mw-le-hamburger" id="toolbar-user-menu-button" @click="userMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div id="user-menu" class="card mw-le-nav-box">
                    <nav>
                        <a v-for="menuItem in menu" :href="menuItem.href">
                            <span v-html="menuItem.icon_html"></span>
                            {{ menuItem.title }}
                        </a>
                    </nav>
                </div>
            </div>
        </div>


    </div>


</template>

<script>
import ResolutionSwitch from "./ResolutionSwitch.vue";
import Editor from "./Editor.vue";
import UndoRedo from "./UndoRedo.vue";
import SaveButton from "./SaveButton.vue";
import * as api from "../../../api-core/services/services/preview.service.js";
import axios from 'axios';

export default {
    components: {SaveButton, UndoRedo, Editor, ResolutionSwitch},
    methods: {
        pagePreviewToggle: () => {
           api.pagePreviewToggle()
        },
        userMenu: () => {
            document.getElementById('user-menu-wrapper').classList.toggle('active');
        },
        async getTopRightMenu () {
            let instance = this;
            await axios.get(route('api.live-edit.get-top-right-menu')).then(function (response) {
                instance.menu = response.data;
            });
        }
    },
    data() {
        return {
            menu: []
        }
    },
    mounted() {

        this.getTopRightMenu();

        mw.app.canvas.on('canvasDocumentClick', () => {
            document.getElementById('user-menu-wrapper').classList.remove('active');
        });
    }
}
</script>
