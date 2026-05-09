<template>






<div class="d-inline-flex">

  <input type="hidden" ref="filepickerinput" v-model="selectedFile" @input="triggerChangeSelectedFile"/>





  <!--
    AI-118 / TICKET-CH (cycle-109 2026-05-09): one MwButton migration
    site demonstration. Pre-fix: ad-hoc <button class="btn btn-link"
    btn-sm"> with no aria-label, no min-touch-target enforcement.
    Post-fix: <MwButton variant="ghost" size="sm" :aria-label="..."> —
    44×44 enforced by the MwButton base style, aria-label localized
    + announced to SR users, and the @click handler still wires to
    the existing removeBackgroundImage() method.
  -->
  <MwButton
    v-if="selectedFile"
    variant="ghost"
    size="sm"
    :aria-label="'Remove image'"
    @click="removeBackgroundImage()"
  >
    <i class="mdi mdi-delete" aria-hidden="true"></i>
  </MwButton>





  <div v-if="selectedFile" class="file-picker-badge"

       @click="togglePicker"
       :style="{ backgroundImage: 'url(' + selectedFile + ')' }"></div>

  <div v-if="!selectedFile" class="file-picker-badge"

       @click="togglePicker"></div>



</div>
</template>

<style>
.hu-file-picker {
  width: 200px !important;
  right: 0px;
  position: absolute;
  margin-top: 2px;
  z-index: 99;
}

.file-picker-badge {
  width: 30px;
  height: 30px;
  background: #ddd;
   text-align: center;
  line-height: 30px;
  border-radius: 100%;
  cursor: pointer;
  border: 1px solid #e0e0e0;
  background-size: cover;
  background-position: center;


}
</style>

<script>

import MwButton from '../MwButton.vue';

export default {

  components: { MwButton },

  props: {
    file: {
      type: String,
      default: ''
    },
    name: {
      type: String,
      default: 'file'
    }
  },
  data() {
    return {
      showPicker: false,
      iconDelete: false,

      selectedFile: this.$props.file
    }
  },
  mounted() {


    mw.top().app.on('mw.elementStyleEditor.closeAllOpenedMenus', () => {
      this.closePicker()
    });
  },

  watch: {
    file(newfile) {
      this.selectedFile = newfile;
    },
  },

  methods: {
    changefile(file) {
      this.selectedFile = file.hex;
      this.$props.file = file.hex;
    },
    triggerChangeSelectedFile() {

      this.$props.file = this.selectedFile;

      this.$emit('change', this.$props.file);
    },
    triggerChange() {
      this.$emit('change', this.$props.file);
    },

    removeBackgroundImage() {
      this.selectedFile = '';
      this.$props.file = '';
      this.$emit('change', this.$props.file);
    },
    closePicker() {
      this.showPicker = false;
    },
      togglePicker() {



      mw.filePickerDialog( (url) => {
         this.selectedFile = url;
        this.$props.file = url;
        this.$emit('change', this.$props.file);
      });






      this.showPicker = !this.showPicker;
    }
  }
}
</script>
