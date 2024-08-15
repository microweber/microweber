<template>
    <div>
        <ColorPicker v-model="boxShadowOptionsGroup.shadowColor"
                     :color="boxShadowOptionsGroup.shadowColor"
                     :label="'Color'"
                     @change="handleBoxShadowOptionChange('shadowColor', $event)"/>
        <SliderSmall label="Horizontal Shadow Length" v-model="boxShadowOptionsGroup.horizontalLength"
                     :min="-300"
                     :max="300" :step="1" @change="handleBoxShadowOptionChange('horizontalLength', $event)"></SliderSmall>
        <SliderSmall label="Vertical Shadow Length" v-model="boxShadowOptionsGroup.verticalLength"
                     :min="-300" :max="300" :step="1" @change="handleBoxShadowOptionChange('verticalLength', $event)"></SliderSmall>
        <SliderSmall label="Blur Radius" v-model="boxShadowOptionsGroup.blurRadius" :min="0" :max="30"
                     :step="1" @change="handleBoxShadowOptionChange('blurRadius', $event)"></SliderSmall>
        <SliderSmall label="Spread Radius" v-model="boxShadowOptionsGroup.spreadRadius" :min="0" :max="30"
                     :step="1" @change="handleBoxShadowOptionChange('spreadRadius', $event)"></SliderSmall>
        <div class="form-group">
            <div class="form-check">
                <label for="inset">Inset</label>
                <input style="margin-inline: -15px 8px;"
                       type="checkbox"
                       id="inset"
                       class="form-check-input"
                       v-model="boxShadowOptionsGroup.inset"
                       @change="handleBoxShadowOptionChange('inset', $event)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import ColorPicker from "./ColorPicker.vue";
import SliderSmall from "./SliderSmall.vue";

export default {
    props: {
        boxShadowOptionsGroup: Object,
        index: Number
    },
    components: {
        ColorPicker,
        SliderSmall
    },
    methods: {
        handleBoxShadowOptionChange(optionName, value) {
             this.boxShadowOptionsGroup[optionName] = value;

             this.$emit('changeOptions', this.boxShadowOptionsGroup, this.index);
        }
    }
};
</script>

