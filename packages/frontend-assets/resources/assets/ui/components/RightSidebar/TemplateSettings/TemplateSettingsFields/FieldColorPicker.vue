<template>
    <div>
        <label class="live-edit-label">{{ setting.title }}</label>
        <input 
            type="color" 
            class="form-control form-control-color" 
            :value="currentValue" 
            @input="updateValue" 
        />
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
            currentValue: ''
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
    },
    methods: {
        updateValue(event) {
            const value = event.target.value;
            this.currentValue = value;
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
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
