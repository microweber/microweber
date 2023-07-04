
<template>
    <div id="toolbar" class="shadow-sm">

        <div class="toolbar-nav toolbar-nav-hover">
            <a class="mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed" href="./">
                <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                    <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                        <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                        <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                </svg>
                Admin
            </a>

            <div class="btn-group">

                <UndoRedo></UndoRedo>
            </div>
        </div>

        <ResolutionSwitch></ResolutionSwitch>
        <Editor></Editor>

        <div class="toolbar-col">
            <div class="toolbar-col-container">
                <div class="btn-group">
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
                        <a onclick="document.body.classList.toggle('theme-dark')">
                            <span>
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="36" viewBox="0 96 960 960" width="36">
                                <path
                                    d="M480 936q-150 0-255-105T120 576q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444 396q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480 936Zm0-80q88 0 158-48.5T740 681q-20 5-40 8t-40 3q-123 0-209.5-86.5T364 396q0-20 3-40t8-40q-78 32-126.5 102T200 576q0 116 82 198t198 82Zm-10-270Z"></path>
                            </svg>
                            </span>
                            Dark mode
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
