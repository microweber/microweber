<template>
    <!--
        task-2026-05-16-481080: match the unified ESE row pattern
        (label-left / control-right) used by DropdownSmall,
        ColorPicker, ImagePicker, Italic toggle. Was a vertical
        stack with the select going full-width below the label —
        inconsistent with sibling fields. The `font-picker-select`
        class shares the `dropdown-small-select` sizing rules from
        general-styles.css below, so the select sits compact
        (~150px) on the right while "Font" anchors left.
    -->
    <div class="form-control-live-edit-label-wrapper my-4" :modelvalue="null">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <label class="live-edit-label mb-0">Font</label>
            <select
                class="form-control-live-edit-input form-select dropdown-small-select font-picker-select"
                @change="handleFontChange"
                :value="fontFamily"
                ref="fontSelect">
                <option value="">Default</option>
                <option value="inherit">Inherit</option>
                <!-- Display custom font if it's not in the supported fonts list -->
                <option
                    v-if="fontFamily && !isInSupportedFonts(fontFamily) && fontFamily !== '' && fontFamily !== 'inherit'"
                    :value="fontFamily"
                    :style="{ fontFamily: `${fontFamily}` }">
                    {{ fontFamily }}
                </option>
                <option
                    v-for="(fontFamilyItem, index) in supportedFonts"
                    :key="index"
                    :value="fontFamilyItem"
                    :selected="fontFamily === fontFamilyItem"
                    :style="{ fontFamily: `${fontFamilyItem}` }">
                    {{ fontFamilyItem }}
                </option>
            </select>
        </div>
        <small class="cursor-pointer d-flex ms-auto justify-content-end pt-2 pb-1 font-picker-add-more" v-on:click="loadMoreFonts()">Add more fonts</small>
    </div>
</template>

<style scoped>
.font-picker-selected-font {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    width: 100%;
    text-overflow: ellipsis;
}

.dropdown-menu {
    width: 100%
}

.dropdown-active > .dropdown-menu {

    display: block;
}


</style>

<script>
export default {
    inheritAttrs: false, // Prevent attributes from being applied to the root element
    props: {
        value: String
    },
    watch: {
        value(newFontFamily) {
            if (typeof newFontFamily === 'string') {
                this.fontFamily = newFontFamily;
                this.updateSelectElement();
            }
        }
    },

    methods: {
        loadMoreFonts() {
            mw.top().app.fontManager.manageFonts();
        },

        isInSupportedFonts(font) {
            return this.supportedFonts.includes(font);
        },

        updateSelectElement() {
            // Ensure the select element reflects the current fontFamily
            this.$nextTick(() => {
                if (this.$refs.fontSelect) {
                    if (this.fontFamily && typeof this.fontFamily === 'string') {
                        this.$refs.fontSelect.value = this.fontFamily;
                    } else {
                        this.$refs.fontSelect.value = '';
                    }
                }
            });
        },

        handleFontChange(event) {
            event.preventDefault();
            // Get the selected value directly from the DOM element
            const selectedValue = event.target.value;

            if (typeof selectedValue === 'string') {
                this.fontFamily = selectedValue;
                // Emit both events for compatibility
                this.$emit('change', selectedValue);
                this.$emit('input', selectedValue);
                // Force update to ensure UI reflects changes
                this.$forceUpdate();
            }
        }
    },

    created() {
        // Initialize fontFamily with a safe value
        if (typeof this.value === 'string') {
            this.fontFamily = this.value;
        } else {
            this.fontFamily = '';
        }
    },

    mounted() {
        // Set up fonts
        setTimeout(() => {
            this.supportedFonts = mw.top().app.fontManager.getFonts();
            this.updateSelectElement();

            mw.top().app.fontManager.subscribe((fonts) => {
                if (fonts) {
                    this.supportedFonts = fonts;
                    this.updateSelectElement();
                }
            });
        }, 1000);
    },

    updated() {
        // Ensure the select element is updated when component updates
        this.updateSelectElement();
    },

    data() {
        return {
            supportedFonts: [],
            fontFamily: '',
        };
    },
};
</script>
