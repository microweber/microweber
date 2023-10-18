<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M12.2,3.9c4.5,0,8.1,3.6,8.1,8.1s-3.6,8.1-8.1,8.1S4.1,16.5,4.1,12S7.7,3.9,12.2,3.9"></path>
        </svg>
        <b class="mw-admin-action-links ms-3" :class="{'active': showBackground }" v-on:click="toggleBackground">
            Background
        </b>
    </div>

    <div v-if="showBackground">

        <ColorPicker v-model="backgroundColor" v-bind:color=backgroundColor :label="'Color'"
                     @change="handleBackgroundColorChange"/>


        <ImagePicker label="Image" v-model="backgroundImage" v-bind:file="backgroundImageUrl"
                     @change="handleBackgroundImageChange"/>

        <DropdownSmall v-model="backgroundSize" :options="backgroundSizeOptions" :label="'Size'"/>


        <DropdownSmall v-model="backgroundRepeat" :options="backgroundRepeatOptions" :label="'Repeat'"/>


        <DropdownSmall v-model="backgroundPosition" :options="backgroundPositionOptions"
                       :label="'Position'"/>


        <DropdownSmall v-model="backgroundClip" :options="backgroundClipOptions"
                       :label="'Clip'"/>



    </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';
import ImagePicker from './components/ImagePicker.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import Slider from '@vueform/slider';
import FilePicker from "../../components/Form/FilePicker.vue";

export default {

    components: {ColorPicker, FontPicker, Dropdown, Input, Slider, FilePicker, ImagePicker, DropdownSmall},

    data() {
        return {
            'showBackground': false,
            'backgroundPositionOptions': [
                {key: "none", value: "None"},
                {key: "0% 0%", value: "Left Top"},
                {key: "50% 0%", value: "Center Top"},
                {key: "100% 0%", value: "Right Top"},
                {key: "0% 50%", value: "Left Center"},
                {key: "50% 50%", value: "Center Center"},
                {key: "100% 50%", value: "Right Center"},
                {key: "0% 100%", value: "Left Bottom"},
                {key: "50% 100%", value: "Center Bottom"},
                {key: "100% 100%", value: "Right Bottom"}
            ],
            'backgroundRepeatOptions': [
                {key: "none", value: "None"},
                {key: "repeat", value: "repeat"},
                {key: "no-repeat", value: "no-repeat"},
                {key: "repeat-x", value: "repeat horizontally"},
                {key: "repeat-y", value: "repeat vertically"}
            ],
            'backgroundClipOptions': [
                {key: "border-box", value: "Border Box"},
                {key: "content-box", value: "Content Box"},
                {key: "text", value: "Text"}
            ],
            'backgroundSizeOptions': [
                {key: "none", value: "None"},
                {key: "auto", value: "Auto"},
                {key: "contain", value: "Fit"},
                {key: "cover", value: "Cover"},
                {key: "100% 100%", value: "Scale"}
            ],

            'activeNode': null,
            'isReady': false,
            'backgroundImage': null,
            'backgroundColor': null,
            'backgroundPosition': null,
            'backgroundRepeat': null,
            'backgroundSize': null,
            'backgroundImageUrl': null,
            'backgroundClip': null,
        };
    },

    methods: {
        toggleBackground: function () {
            this.showBackground = !this.showBackground;
            this.emitter.emit('element-style-editor-show', 'background');
        },

        resetAllProperties: function () {
            this.backgroundImage = null;
            this.backgroundImageUrl = null;
            this.backgroundColor = null;
            this.backgroundPosition = null;
            this.backgroundRepeat = null;
            this.backgroundSize = null;
            this.backgroundClip = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssBackground(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssBackground: function (css) {
            if (!css || !css.get) return;
            var bg = css.get.background();

            if (bg.image) {
                if (bg.image.indexOf('url(') !== -1) {
                    this.backgroundImageUrl = bg.image.replace('url(', '').replace(')', '');
                    //also replace "
                    this.backgroundImageUrl = this.backgroundImageUrl.replace(/\"/g, "");
                }
            }
            this.backgroundImage = bg.image;
            this.backgroundColor = bg.color;
            this.backgroundPosition = bg.position;
            this.backgroundRepeat = bg.repeat;
            this.backgroundSize = bg.size;
            this.backgroundClip = bg.clip;
        },

        handleBackgroundColorChange: function (color) {

            if (typeof (color) != 'string') {
                return;
            }
            this.backgroundColor = color
        },
        handleBackgroundImageChange: function (url) {
            var urlVal = url;
            if (url && url != '' && url != 'none' && url != 'inherit' && url != 'initial') {
                //check if contain url(
                this.backgroundImageUrl = url;
                if (url.indexOf('url(') === -1) {
                    urlVal = 'url(' + url + ')';
                }
            } else {
                this.backgroundImageUrl = '';
            }
            if (urlVal == null) {
                urlVal = 'none';
            }
            this.backgroundImage = urlVal;
        },

        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                    node: this.activeNode,
                    prop: prop,
                    val: val
                });
            }
        },

    },
    mounted() {

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'background') {
                this.showBackground = false;
            }
        });

        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {

            this.populateStyleEditor(element)

        });

    },

    watch: {
        // Background-related property watchers
        backgroundImage: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundImage', newValue);
        },
        backgroundClip: function (newValue, oldValue) {
            if (newValue == 'text') {
                this.applyPropertyToActiveNode('backgroundClip', 'text');
                this.applyPropertyToActiveNode('-webkitBackgroundClip', 'text');
                this.applyPropertyToActiveNode('color', 'rgba(0,0,0,0)');
            } else {
                this.applyPropertyToActiveNode('backgroundClip', newValue);
                this.applyPropertyToActiveNode('-webkitBackgroundClip', newValue);
                this.applyPropertyToActiveNode('color', '');

            }
        },
        backgroundColor: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundColor', newValue);
        },
        backgroundPosition: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundPosition', newValue);
        },
        backgroundRepeat: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundRepeat', newValue);
        },
        backgroundSize: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundSize', newValue);
        },
    },
}
</script>
