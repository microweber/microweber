<template>
    <div class="mt-2">
        <label v-if="setting.title" class="live-edit-label">{{ setting.title }}</label>
        <div v-if="setting.description" class="mb-2">
            <small>{{ setting.description }}</small>
        </div>
        
        <div v-if="setting.fieldSettings && setting.fieldSettings.colors" class="color-palette-container">
            <div v-for="(colorPalette, index) in setting.fieldSettings.colors" :key="index" 
                 class="mt-2 color-palette-item" @click="applyColorPalette(colorPalette)">
                <div class="d-flex flex-cols gap-2 cursor-pointer">
                    <div v-if="colorPalette.name" 
                         class="color-swatch"
                         :style="{ background: colorPalette.name }">
                    </div>
                    
                    <div v-for="(mainColor, colorIndex) in colorPalette.mainColors" :key="colorIndex"
                         class="color-swatch"
                         :style="{ background: mainColor }">
                    </div>
                </div>
                
                <div v-if="colorPalette.label" class="palette-label mt-1">
                    <small>{{ colorPalette.label }}</small>
                </div>
            </div>
        </div>
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
        applyColorPalette(colorPalette) {
            if (!window.mw?.top()?.app?.cssEditor) return;
            
            if (colorPalette.properties) {
                Object.keys(colorPalette.properties).forEach(property => {
                    const value = colorPalette.properties[property];
                    window.mw.top().app.cssEditor.setPropertyForSelector(
                        this.selectorToApply, 
                        property, 
                        value, 
                        true, 
                        true
                    );
                });
            }
            
            this.$emit('update', {
                selector: this.selectorToApply,
                palette: colorPalette
            });
        }
    }
};
</script>

<style scoped>
.color-palette-container {
    max-height: 400px;
    overflow-y: auto;
}

.color-palette-item {
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: background-color 0.2s;
}

.color-palette-item:hover {
    background-color: var(--tblr-bg-surface, #f8f9fa);
}

.color-swatch {
    border-radius: 6px;
    width: 100%;
    height: 40px;
    min-width: 40px;
    border: 1px solid rgba(0,0,0,0.1);
}

.palette-label {
    text-align: center;
    color: var(--tblr-text-muted, #6c757d);
}
</style>
