<template>
    <div class="s-field">
        <label>{{ label }}</label>
        <div class="s-field-content">
            <div class="mw-ui-btn-nav background-image-nav">
        <span
            class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px background-select-item"
            @click="selectBackgroundImage"
            data-tip="Select background image"
        >
          <span v-if="!selectedFile" class="background-preview" style="background-image: none;"></span>
          <span v-if="selectedFile" class="background-preview" :style="{ backgroundImage: `url(${selectedFile})` }"></span>
        </span>
                <span
                    class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-delete"
                    @click="removeBackgroundImage"
                    data-tip="Remove background"
                    data-tipposition="top-right"
                ></span>
                <span
                    class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-history"
                    @click="resetBackgroundImage"
                    data-tip="Reset background"
                    data-tipposition="top-right"
                ></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: 'Image', // Default label text
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
            // Example: this.selectedFile = 'default-image-url.jpg';
            this.$emit('change', this.selectedFile);
        },
    },
};
</script>

<style>
/* Add your CSS styles here */
</style>
