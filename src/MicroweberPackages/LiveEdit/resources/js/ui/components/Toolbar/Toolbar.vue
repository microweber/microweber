

<style>
.back-to-edit{
    position: fixed;
    top: -80px;
    right: -80px;
    width: 160px;
    height: 160px;
    cursor: pointer;
    background-color: #fff;
    z-index: 10;
    text-align: center;
    padding: 70px 95px 0 0;
    box-shadow: 0 0 10px #0000002b;
    transform: scale(0) rotate(-45deg);
    transition: .3s;
    visibility: hidden;
    pointer-events: none;
    opacity: 0;
}

html.preview .back-to-edit{
    transform: scale(1) rotate(-45deg);
    visibility: visible;
    pointer-events: all;
    opacity: 1;
}

.back-to-edit svg{
    transform: rotate(45deg);
    width: 24px;
}

</style>
<template>
    <div id="toolbar" class="shadow-sm" :style="{'display': toolbarDisplay}">
        <div class="toolbar-nav toolbar-nav-hover col-xxl-3 col-auto d-flex justify-content-lg-start">

            <a id="mw-live-edit-toolbar-back-to-admin-link" class="mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed" :href="backToAdminLink">
                <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                    <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                        <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                        <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                    </g>
                </svg>
                 <span class="ms-1 font-weight-bold">ADMIN</span>
            </a>

            <div class="live-edit-undo-redo-buttons-wrapper ms-1">
                <UndoRedo></UndoRedo>
            </div>

            <div class="live-edit-add-content-button-wrapper ms-1">
                <AddContentButton></AddContentButton>
            </div>
        </div>


        <div class="col-md-3 col">
           <ContentSearchNav></ContentSearchNav>
        </div>


        <div class="toolbar-col col-auto ms-sm-0 ms-2">
            <div class="toolbar-col-container">
                <div class="d-flex align-items-center">
                    <ResolutionSwitch></ResolutionSwitch>
                    <SettingsCustomize></SettingsCustomize>

                    <ToolbarMulilanguageSelector></ToolbarMulilanguageSelector>


                    <HtmlEditor></HtmlEditor>

                    <span class="back-to-edit" @click="pagePreviewToggle()" title="Back to edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"></path></svg>
                    </span>
                    <button class="btn live-edit-toolbar-buttons live-edit-toolbar-buttons-view me-2" @click="pagePreviewToggle()">
                        VIEW
                    </button>
                    <SaveButton></SaveButton>
                </div>
            </div>

            <div id="user-menu-wrapper" class="align-self-center">
                <span class="mw-le-hamburger" id="toolbar-user-menu-button" @click="userMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div id="user-menu" class="card mw-le-nav-box">
                    <nav>
                        <a v-for="menuItem in menu" :href="menuItem.href" :id="menuItem.id" :ref="menuItem.ref?menuItem.ref:''" :class="menuItem.class?menuItem.class:''">
                            <span v-html="menuItem.icon_html"></span>
                            {{ menuItem.title }}

                        </a>
                        <a v-on:click="this.toggleDarkMode()">
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

    <Editor></Editor>


</template>

<script>
import ResolutionSwitch from "./ResolutionSwitch.vue";
import Editor from "./Editor.vue";
import UndoRedo from "./UndoRedo.vue";
import SaveButton from "./SaveButton.vue";
import ContentSearchNav from "./ContentSearchNav.vue";
import SettingsCustomize from "./SettingsCustomize.vue";

import * as api from "../../../api-core/services/services/preview.service.js";
import axios from 'axios';
import StyleEditor from "../StyleEditor/StyleEditor.vue";
import HtmlEditor from "../HtmlEditor/HtmlEditor.vue";
import AddContentButton from "./AddContentButton.vue";
import ToolbarMulilanguageSelector from "./ToolbarMulilanguageSelector.vue";

export default {
    components: {
        ToolbarMulilanguageSelector,
        AddContentButton,
        HtmlEditor,
        SaveButton, UndoRedo, Editor, ResolutionSwitch, ContentSearchNav, SettingsCustomize,},
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
        },
        toggleDarkMode: () => {
            var is_dark = $("body").hasClass('theme-dark');
            if (!is_dark) {
                mw.cookie.set('admin_theme_dark', 'true');

            } else {

                mw.cookie.delete('admin_theme_dark');
            }
            var canvasWindow = mw.top().app.canvas.getWindow();
            mw.tools.eachWindow(function () {
                if(this.mw && this !== canvasWindow ){
                    console.log(this.document.body)
                    if (!is_dark) {
                        $("body", this.document).addClass('theme-dark')
                        $("#navbar-change-theme-icon-dark", this.document).show()
                        $("#navbar-change-theme-icon-light", this.document).hide()
                    } else {
                        $("body", this.document).removeClass('theme-dark')
                        $("#navbar-change-theme-icon-light", this.document).show()
                        $("#navbar-change-theme-icon-dark", this.document).hide()
                    }
                }
            })




        },

    },
    data() {
        return {
            menu: [],
            toolbarDisplay: 'none',
            backToAdminLink: ''
        }
    },
    mounted() {

        this.getTopRightMenu();
        const userMenuWrapper = document.getElementById('user-menu-wrapper')

        document.addEventListener('click', e => {
            if(!userMenuWrapper.contains(e.target)) {
                userMenuWrapper.classList.remove('active');
            }
        });
        document.querySelector('#live-edit-app').style.display = this.toolbarDisplay;

        let _ready = 0;

        const _handleReady = () => {
            if(_ready < 2) return;
            setTimeout(() => {
                this.toolbarDisplay = '';
                document.querySelector('#live-edit-app').style.display = this.toolbarDisplay;
            }, 700);

        }

        window.addEventListener('load', () => {
            _ready++
            _handleReady()
        })

        mw.app.canvas.on('liveEditCanvasLoaded', () => {

            _ready++;
            _handleReady();



            setTimeout(() =>  {
                if(mw.app.isPreview()) {
                    api.previewMode();

                } else {
                    api.liveEditMode();
                }
            }, 10);



            var liveEditIframeData = mw.top().app.canvas.getLiveEditData();
            if (document.getElementById('js-live-edit-back-to-admin-link')) {
                this.backToAdminLink = document.getElementById('js-live-edit-back-to-admin-link').href;

            }
            if (liveEditIframeData && liveEditIframeData.content) {

                this.backToAdminLink = liveEditIframeData.back_to_admin_link;


                this.backToAdminLink = liveEditIframeData.content.content_edit_link;
                if (liveEditIframeData.content.is_home) {
                    this.backToAdminLink = liveEditIframeData.back_to_admin_link;
                }

                if (liveEditIframeData.content_link) {
                    var liveEditWebsitePreviewLink = document.getElementById('js-live-edit-website-preview-link');
                    if (liveEditWebsitePreviewLink) {
                        liveEditWebsitePreviewLink.href = liveEditIframeData.content_link;
                        liveEditWebsitePreviewLink.target = '_blank';
                    }

                }

            }


        })
        mw.app.canvas.on('canvasDocumentClick', () => {
            userMenuWrapper.classList.remove('active');
        });
    }
}
</script>
