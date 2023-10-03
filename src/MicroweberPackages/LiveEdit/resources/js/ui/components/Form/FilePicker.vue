<template>

  <input type="text" ref="filepickerinput" v-model="selectedFile" @input="triggerChangeSelectedFile"/>



  <div class="file-picker-badge"

       @click="togglePicker"
       :style="{background: file}"></div>


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
  file: #fff;
  text-align: center;
  line-height: 30px;
  border-radius: 100%;
  cursor: pointer;
  border: 1px solid #e0e0e0;
}
</style>

<script>

export default {

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


    closePicker() {
      this.showPicker = false;
    },
    togglePicker() {



//       var picker = new mw.filePicker({
//         type: 'image',
//          element: this.$refs.colorpickerinput,
//         label: false,
//         autoSelect: true,
//         footer: false,
//         _frameMaxHeight: true,
//         fileUploaded: function (file) {
//
//           alert(file)
//         },
//         onResult: function (file) {
// alert(file)
//
//         }
//
//       });



      // let filePicker = mw.app.filePicker.openfilePicker(this.selectedFile, file => {
      //   this.$props.file = file;
      //   this.$emit('change', this.$props.file);
      //
      // });

      this.showPicker = !this.showPicker;
    }
  }
}
</script>
