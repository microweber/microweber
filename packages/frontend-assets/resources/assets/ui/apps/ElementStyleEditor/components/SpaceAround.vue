<!--
  LE redesign (frame 1c) — reusable "Space around" preset control with a
  Padding/Margin mode dropdown. Presentational + emit-only (same contract idea
  as Align.vue): the parent owns applying the value to the node and reading the
  current value per mode, so this can be reused anywhere a None/S/M/L
  padding-or-margin segmented is needed.

  Props:
    label   — field label (default "Space around")
    value   — current size in px (Number|String) for the active mode (active-state match)
    mode    — 'padding' | 'margin' (which property the presets write)
    presets — [{ key, label, px }] segmented options
    modes   — [{ key, label }] dropdown options
  Emits:
    update({ px, mode }) — chosen preset value + current mode
    update:mode(mode)    — mode changed via the dropdown
-->
<template>
    <div class="form-control-live-edit-label-wrapper mw-ese-space">
        <div class="mw-ese-space__head">
            <label class="live-edit-label">{{ label }}</label>
            <select class="mw-ese-space__mode"
                    v-model="selectedMode"
                    @change="onModeChange"
                    aria-label="Apply spacing as padding or margin">
                <option v-for="m in modes" :key="m.key" :value="m.key">{{ m.label }}</option>
            </select>
        </div>
        <div class="mw-segmented mw-ese-seg">
            <span v-for="sp in presets" :key="sp.key"
                  class="mw-segmented__cell"
                  :class="{ 'active': isActive(sp.px), 'is-active': isActive(sp.px) }"
                  role="button" tabindex="0"
                  :aria-label="label + ' ' + sp.label"
                  :aria-pressed="isActive(sp.px) ? 'true' : 'false'"
                  @click="select(sp.px)"
                  @keydown.enter.prevent="select(sp.px)"
                  @keydown.space.prevent="select(sp.px)">
                {{ sp.label }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: 'Space around',
        },
        value: {
            type: [Number, String],
            default: null,
        },
        mode: {
            type: String,
            default: 'padding',
        },
        presets: {
            type: Array,
            default: () => ([
                { key: 'none', label: 'None', px: 0 },
                { key: 's', label: 'S', px: 8 },
                { key: 'm', label: 'M', px: 16 },
                { key: 'l', label: 'L', px: 32 },
            ]),
        },
        modes: {
            type: Array,
            default: () => ([
                { key: 'padding', label: 'Padding' },
                { key: 'margin', label: 'Margin' },
            ]),
        },
    },
    data() {
        return {
            selectedMode: this.mode,
        };
    },
    watch: {
        mode(v) {
            this.selectedMode = v;
        },
    },
    methods: {
        isActive(px) {
            return parseInt(this.value, 10) === px;
        },
        select(px) {
            this.$emit('update', { px: px, mode: this.selectedMode });
        },
        onModeChange() {
            this.$emit('update:mode', this.selectedMode);
        },
    },
};
</script>
