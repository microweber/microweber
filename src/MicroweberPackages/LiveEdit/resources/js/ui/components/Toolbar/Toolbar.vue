
<template>
    <div id="toolbar" class="shadow-sm">
        <div class="toolbar-nav toolbar-nav-hover">
            <a href="./" class="mw-le-btn mw-le-btn-icon mw-le-btn-primary2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 32px;">
                    <path d="M21,11H6.83L10.41,7.41L9,6L3,12L9,18L10.41,16.58L6.83,13H21V11Z"/>
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
                </div>
            </div>
            <span style="width: 50px"></span>
            <span class="btn btn-icon btn-light" id="preview-button" @click="pagePreviewToggle()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20"><path
                        d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/></svg>
                </span>
            <div id="user-menu-wrapper">
                <span class="mw-le-hamburger" id="toolbar-user-menu-button" @click="userMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div id="user-menu" class="card mw-le-nav-box">
                    <div class="card-body" id="user-menu-header">
                        <small>Project</small>
                        <h3>Boris Website</h3>
                        <span class=" btn  btn-sm ">
                            In Test Period
                        </span>
                        <span class="btn btn-sm btn-primary">
                            Upgrade
                        </span>
                    </div>
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
