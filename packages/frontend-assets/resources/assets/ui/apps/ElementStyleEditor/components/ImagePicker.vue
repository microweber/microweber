<template>
 <div class="form-control-live-edit-label-wrapper my-4 background-image-nav d-flex align-items-center justify-content-between">
    <label class="live-edit-label col px-0 text-start">Background Image</label>
    <div class="d-flex col-auto justify-content-end">

        <div
            class="btn live-edit-toolbar-buttons live-edit-toolbar-buttons-view background-select-item d-flex justify-content-end"
            @click="selectBackgroundImage"
            data-tip="Select background image"
        >
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="20" viewBox="0 -960 960 960" width="20"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/></svg>
          <span v-if="!selectedFile" class="background-preview" style="background-image: none;"></span>
          <span v-if="selectedFile" class="background-preview" :style="{ backgroundImage: `url(${selectedFile})` }"></span>
    </div>


            <div class="mw-action-buttons-background-circle-on-hover"
                  @click="removeBackgroundImage"
                  data-tip="Remove background"
                  data-tipposition="top-right"
                  v-if="selectedFile"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: 'Background Image', // Default label text
        },
        file: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            selectedFile: this.file,
        };
    },
  watch: {
    file(newfile) {
      this.selectedFile = newfile;
    },
  },
    methods: {
        selectBackgroundImage() {
            mw.filePickerDialog( (url) => {
                this.selectedFile = url;

                this.$emit('change', this.selectedFile);
            });


        },
        removeBackgroundImage() {
            this.selectedFile = '';
            this.$emit('change', this.selectedFile);
        },
        resetBackgroundImage() {
            // Implement your reset logic here, e.g., set 'selectedFile' to a default image.
            // Exadmple: this.selectedFile = 'default-image-url.jpg';
            this.$emit('change', this.selectedFile);
        },
    },
};
</script>

<style>
/* Add your CSS styles here */
</style>
