<template>
  <div class="my-4 pb-4">
        <label class="live-edit-label">{{ label }}</label>
        <div class="ms-2">
            <div class="background-image-nav d-flex justify-content-between align-items-center">
                <div>
                    <span
                        class="mw-ui-btn mw-ui-btn-outline rounded-0 tip background-select-item" style="height: 70px; width: 70px; border: 1px solid #000;"
                        @click="selectBackgroundImage"
                        data-tip="Select background image"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/></svg>
                  <span v-if="!selectedFile" class="background-preview" style="background-image: none;"></span>
                  <span v-if="selectedFile" class="background-preview" :style="{ backgroundImage: `url(${selectedFile})` }"></span>
                </span>
                </div>

                <div v-if="selectedFile" class="d-flex gap-2 justify-content-end">
                    <span class="mw-action-buttons-background-circle-on-hover"
                        @click="removeBackgroundImage"
                        data-tip="Remove background"
                        data-tipposition="top-right"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                    </span>
                    <span class="mw-action-buttons-background-circle-on-hover"
                        @click="resetBackgroundImage"
                        data-tip="Reset background"
                        data-tipposition="top-right"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"></path></svg>
                    </span>
                </div>
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
