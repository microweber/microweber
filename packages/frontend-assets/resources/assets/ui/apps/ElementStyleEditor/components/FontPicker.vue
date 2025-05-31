<template>
    <div class="form-control-live-edit-label-wrapper my-4" :modelvalue="null">
        <label class="live-edit-label">Font</label>
        <select
            class="form-control-live-edit-input form-select"
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
        <small class="cursor-pointer d-flex ms-auto justify-content-end pt-3 pb-1" v-on:click="loadMoreFonts()">Add more fonts</small>
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
