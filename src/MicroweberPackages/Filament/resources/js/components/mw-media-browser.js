export default function mwMediaManagerComponent({mediaIds}) {
    return {
        mediaIds,
        modalImageSettingsOpen: false,
        selectedImages: [],

        init() {


        },

        editMediaOptionsById(id) {
            this.$wire.mountAction('editAction', {id: id})
        },

        async bulkDeleteSelectedMedia() {
            const dialogConfirm = await mw.confirm('Are you sure you want to delete selected images?').promise()
            if (dialogConfirm) {
                $wire.dispatchFormEvent('mwMediaBrowser::deleteMediaItemsByIds','{{ $statePath }}',
                    { ids: this.selectedImages }
                )
            }
        },

        async deleteMediaById(id) {

            const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
            if (dialogConfirm) {
                $wire.dispatchFormEvent('mwMediaBrowser::deleteMediaItemById','{{ $statePath }}',
                    { id: id }
                )
            }
        },
        async editImageFilename(id, url) {

            const editedImage = await mw.top().app.editImageDialog.editImageUrl(url);

            $wire.dispatchFormEvent('mwMediaBrowser::updateImageFilename','{{ $statePath }}', {
                data: { id: id, filename: editedImage }
            })

        }
    }
}
