<template>
    <div class="mt-2" style="padding: 12px 0;">
        <div>
            <b>{{ setting.title }}</b>
        </div>
        <div class="mt-1">
            <small>{{ setting.description }}</small>
        </div>
        <button @click="clearAll" class="btn btn-outline-dark" style="width:100%;margin-top:15px">
            <i class="fa fa-trash"></i> &nbsp; {{ setting.title }}
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
        },
        rootSelector: {
            type: String,
            default: ''
        }
    },
    methods: {
        clearAll() {
            if (!this.setting.fieldSettings || !this.setting.fieldSettings.properties) return;

            window.mw.confirm('Are you sure you want to clear styles?', () => {
                const updates = [];
                for (const property of this.setting.fieldSettings.properties) {
                    updates.push({
                        selector: this.selectorToApply,
                        property: property,
                        value: '' // Reset value to empty
                    });

                    // If a rootSelector is also provided and relevant for clearing, add it to updates too.
                    // This depends on how `clearAll` is intended to behave with root selectors.
                    if (this.rootSelector && this.rootSelector !== this.selectorToApply) { // Avoid duplicate if they are the same
                         updates.push({
                            selector: this.rootSelector,
                            property: property,
                            value: '' // Reset value to empty
                        });
                    }
                }
                if (updates.length > 0) {
                    this.$emit('batch-update', updates);
                }
            });
        }
    }
};
</script>
