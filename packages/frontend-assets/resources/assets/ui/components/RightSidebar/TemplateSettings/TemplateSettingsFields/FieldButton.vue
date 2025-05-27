<template>
    <div class="mt-2" style="background:var(--tblr-bg-surface);border-radius:8px;padding: 12px;">
        <div>
            <b>{{ setting.title }}</b>
        </div>
        <div class="mt-1">
            <small>{{ setting.description }}</small>
        </div>
        <button @click="executeAction" 
                class="btn btn-outline-dark" 
                style="width:100%;margin-top:15px">
            &nbsp; {{ setting.title }}
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
    methods: {
        executeAction() {
            if (this.setting.onClick) {
                try {
                    // Execute the onClick function safely
                    const func = new Function('event', this.setting.onClick);
                    func.call(this, event);
                } catch (error) {
                    console.error('Error executing button action:', error);
                }
            }
            
            this.$emit('update', {
                selector: this.selectorToApply,
                action: 'button_clicked',
                setting: this.setting
            });
        }
    }
};
</script>
