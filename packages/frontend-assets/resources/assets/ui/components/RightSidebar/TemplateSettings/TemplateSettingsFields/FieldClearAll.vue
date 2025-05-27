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
            if (!window.mw?.top()?.app?.cssEditor) return;
            
            window.mw.confirm('Are you sure you want to clear styles?', () => {
                for (const property of this.setting.fieldSettings.properties) {
                    window.mw.top().app.cssEditor.setPropertyForSelector(
                        this.selectorToApply, 
                        property, 
                        '', 
                        true, 
                        true
                    );
                    
                    if (this.rootSelector) {
                        window.mw.top().app.cssEditor.setPropertyForSelector(
                            this.rootSelector, 
                            property, 
                            '', 
                            true, 
                            true
                        );
                    }
                }
            });
        }
    }
};
</script>
