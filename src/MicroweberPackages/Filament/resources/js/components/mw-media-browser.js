export default function mwMediaManagerComponent({mediaIds}) {
    return {
        mediaIds,
        modalImageSettingsOpen: false,
        showBulkDeleteButton: false,
        selectedImages: [],

        init() {
            this.$watch('selectedImages', (value) => {
                this.showBulkDeleteButton = value.length > 0 && this.selectedImages && this.selectedImages.length > 0;
            });
        },

        editMediaOptionsById(id) {
            // Get the current state path from the form event
            const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
            this.$wire.mountFormComponentAction(statePath, 'edit', {id: id});
        },

        selectAllMedia() {
            const checkboxes = document.querySelectorAll('.admin-thumb-item input[type="checkbox"]');
            const allIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            this.selectedImages = allIds;
        },

        deselectAllMedia() {
            this.selectedImages = [];
        },

        bulkDeleteSelectedMedia() {
            if (this.selectedImages && this.selectedImages.length > 0) {
                if (confirm('Are you sure you want to delete the selected images?')) {
                    const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
                    this.$wire.dispatchFormEvent('mwMediaBrowser::deleteMediaItemsByIds', statePath, {
                        ids: this.selectedImages
                    });
                    this.selectedImages = [];
                }
            }
        },

        async deleteMediaById(id) {
            const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
            if (dialogConfirm) {
                const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
                this.$wire.dispatchFormEvent('mwMediaBrowser::deleteMediaItemById', statePath, {
                    id: id
                });
            }
        },

        async editImageFilename(id, url) {
            const editedImage = await mw.top().app.editImageDialog.editImageUrl(url);
            const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
            this.$wire.dispatchFormEvent('mwMediaBrowser::updateImageFilename', statePath, {
                data: { id: id, filename: editedImage }
            });
        }
    }
}
