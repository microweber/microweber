<template>
    <div>
        <label class="live-edit-label">{{ setting.title }}</label>
        <select class="form-select" :value="currentValue" @change="updateValue">
            <option v-for="font in availableFonts" :key="font" :value="font">
                {{ font }}
            </option>
        </select>
        <button type="button" class="btn btn-outline-dark btn-sm mt-2" @click="loadMoreFonts">
            Load more fonts
        </button>
    </div>
</template>

<script>
export default {
    props: {
        setting: {
            type: Object,
            required: true
        },
        selectorToApply: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            currentValue: '',
            availableFonts: ['Arial', 'Helvetica', 'Times New Roman', 'Georgia', 'Courier New']
        };
    },
    mounted() {
        if (window.mw?.top()?.app?.cssEditor) {
            this.currentValue = window.mw.top().app.cssEditor.getPropertyForSelector(
                this.selectorToApply, 
                this.setting.fieldSettings.property
            ) || '';
        }
        
        if (window.mw?.top()?.app) {
            window.mw.top().app.on('setPropertyForSelector', this.onPropertyChange);
        }

        this.initializeFonts();
    },
    methods: {
        initializeFonts() {
            if (window.mw?.top()?.app?.fontManager) {
                window.mw.top().app.fontManager.subscribe((fonts) => {
                    if (fonts) {
                        this.availableFonts = fonts;
                    }
                });
            }
        },
        updateValue(event) {
            const value = event.target.value;
            this.currentValue = value;
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
        },
        loadMoreFonts() {
            if (window.mw?.top()?.app?.fontManager) {
                window.mw.top().app.fontManager.manageFonts();
            }
        },
        onPropertyChange(event) {
            if (event.selector === this.selectorToApply && 
                event.property === this.setting.fieldSettings.property) {
                this.currentValue = event.value || '';
            }
        }
    },
    beforeUnmount() {
        if (window.mw?.top()?.app) {
            window.mw.top().app.off('setPropertyForSelector', this.onPropertyChange);
        }
    }
};
</script>
