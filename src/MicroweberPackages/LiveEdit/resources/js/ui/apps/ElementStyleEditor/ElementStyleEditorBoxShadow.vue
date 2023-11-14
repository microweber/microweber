<template>
    <div>
        <div class="box-shadow-options">
            <div class="form-group">
                <label for="horizontalLength">Horizontal Shadow Length</label>
                <input
                    type="text"
                    id="horizontalLength"
                    v-model="boxShadowOptions.horizontalLength"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="verticalLength">Vertical Shadow Length</label>
                <input
                    type="text"
                    id="verticalLength"
                    v-model="boxShadowOptions.verticalLength"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="blurRadius">Blur Radius</label>
                <input
                    type="text"
                    id="blurRadius"
                    v-model="boxShadowOptions.blurRadius"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="spreadRadius">Spread Radius</label>
                <input
                    type="text"
                    id="spreadRadius"
                    v-model="boxShadowOptions.spreadRadius"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="shadowColor">Shadow Color</label>
                <input
                    type="color"
                    id="shadowColor"
                    v-model="boxShadowOptions.shadowColor"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="shadowOpacity">Shadow Color Opacity</label>
                <input
                    type="range"
                    id="shadowOpacity"
                    v-model="boxShadowOptions.shadowOpacity"
                    min="0"
                    max="1"
                    step="0.01"
                    @input="applyBoxShadow"
                />
            </div>
            <div class="form-group">
                <label for="inset">Inset</label>
                <input
                    type="checkbox"
                    id="inset"
                    v-model="boxShadowOptions.inset"
                    @change="applyBoxShadow"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            boxShadowOptions: {
                horizontalLength: '2px',
                verticalLength: '4px',
                blurRadius: '13px',
                spreadRadius: '-47px',
                shadowColor: 'rgb(0,0,0)',
                shadowOpacity: 0.61,
                inset: false,
            },
        };
    },
    methods: {
        applyBoxShadow() {
            const {
                horizontalLength,
                verticalLength,
                blurRadius,
                spreadRadius,
                shadowColor,
                shadowOpacity,
                inset,
            } = this.boxShadowOptions;

            const boxShadowValue = `${inset ? 'inset ' : ''}${horizontalLength} ${verticalLength} ${blurRadius} ${spreadRadius} ${shadowColor} / ${shadowOpacity}`;

            // You can emit this value or perform other actions based on your requirements
            console.log(boxShadowValue);
        },
    },
};
</script>




