<template>
    <div class="predefined-text-shadows-select">
        <div class="row">
            <div class="col-4 mb-2" v-for="(shadow, index) in predefinedShadows" :key="index">
                <div class="predefined-text-shadow-item"
                     :class="{ 'active': selectedShadow === shadow.value }"
                     @click="selectShadow(shadow.value)">
                    <div class="text-shadow-preview" :style="{ textShadow: shadow.value }">
                        Text
                    </div>
                    <small class="text-shadow-name">{{ shadow.name }}</small>
                </div>
            </div>
            <div class="col-4 mb-2">
                <div class="predefined-text-shadow-item"
                     :class="{ 'active': selectedShadow === 'custom' }"
                     @click="selectShadow('custom')">
                    <div class="text-shadow-preview custom-preview">
                        <i class="fa fa-cog"></i>
                    </div>
                    <small class="text-shadow-name">Customize</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        predefinedShadows: {
            type: Array,
            default: () => []
        },
        selectedShadow: {
            type: String,
            default: ''
        }
    },
    methods: {
        selectShadow(shadowValue) {
            this.$emit('update:selectedShadow', shadowValue);
        }
    }
}
</script>

<style scoped>
.predefined-text-shadow-item {
    border: 1px solid var(--ese-border, rgba(15, 23, 42, 0.12));
    border-radius: 4px;
    padding: 8px;
    cursor: pointer;
    text-align: center;
    transition: all 0.2s ease;
}

.predefined-text-shadow-item:hover {
    border-color: var(--ese-accent, #182433);
}

.predefined-text-shadow-item.active {
    border-color: var(--ese-accent, #182433);
    background-color: var(--ese-accent-soft, rgba(24, 36, 51, 0.10));
}

.text-shadow-preview {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    color: var(--ese-text, #0f172a);
}

.custom-preview {
    color: var(--ese-accent, #182433);
    font-size: 18px;
}

.text-shadow-name {
    display: block;
    margin-top: 4px;
    color: var(--ese-text-muted, #64748b);
    font-size: 11px;
}
</style>

