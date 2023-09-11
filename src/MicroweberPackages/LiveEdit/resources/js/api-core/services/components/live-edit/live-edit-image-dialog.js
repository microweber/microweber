import BaseComponent from "../../containers/base-class";

export class LiveEditImageDialog extends BaseComponent {
    constructor() {
        super();


    }

    async editImageUrl(url) {
        return new Promise(resolve => {
            var editor = document.createElement('div');
            editor.style.height = 'calc(100vh - 300px)';
            editor.style.minHeight = '410px';
            const footer = mw.element(`
                        <div class="d-flex justify-content-between w-100">
                            <button class="btn" data-action="cancel">Cancel</button>
                            <button class="btn btn-primary" data-action="save">Update</button>
                        </div>
                    `);


            let imageEditor;

            const dlg = mw.dialog({
                width: 1000,
                title: mw.lang("Edit image"),
                content: editor,
                footer: footer.get(0),
                id: 'mw-edit-image--dialog',
            });

            footer.find('[data-action="cancel"]').on('click', function () {
                dlg.remove();
                resolve();
            });

            footer.find('[data-action="save"]').on('click', function () {


                var _img = new Image();
                _img.src = imageEditor.getCurrentImgData().imageData.imageBase64
                mw.top().app.normalizeBase64Image(_img, function () {
                    console.log(this);
                    resolve(this.src);
                })

                dlg.remove();

            })
            $(dlg).on('remove', () => {
                resolve()
                mw.app.liveEdit.play();
            })

            imageEditor = editImage(url, editor, dlg)
        });
    }

}

export default LiveEditImageDialog;
