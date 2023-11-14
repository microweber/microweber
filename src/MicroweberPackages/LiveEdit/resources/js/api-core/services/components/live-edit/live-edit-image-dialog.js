import BaseComponent from "../../containers/base-class";
import FilerobotImageEditor from "filerobot-image-editor";



const { TABS, TOOLS } = FilerobotImageEditor;

const editImage = (url, target, dialog) => {
    const config = {
        source: url,

        showCanvasOnly: false,

        annotationsCommon: {
            fill: '#ff0000',
        },
        Text: { text: 'Double click to edit text' },
        Rotate: { angle: 90, componentType: 'slider' },
        translations: {
            profile: 'Profile',
            coverPhoto: 'Cover photo',
            facebook: 'Facebook',
            socialMedia: 'Social Media',
            fbProfileSize: '180x180px',
            fbCoverPhotoSize: '820x312px',
        },
        Crop: {
            presetsItems: [
                {
                    titleKey: 'classicTv',
                    descriptionKey: '4:3',
                    ratio: 4 / 3,
                },
                {
                    titleKey: 'cinemascope',
                    descriptionKey: '21:9',
                    ratio: 21 / 9,
                },
            ],
            presetsFolders: [
                {
                    titleKey: 'socialMedia',
                    groups: [
                        {
                            titleKey: 'facebook',
                            items: [
                                {
                                    titleKey: 'profile',
                                    width: 180,
                                    height: 180,
                                    descriptionKey: 'fbProfileSize',
                                },
                                {
                                    titleKey: 'coverPhoto',
                                    width: 820,
                                    height: 312,
                                    descriptionKey: 'fbCoverPhotoSize',
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        tabsIds: [TABS.FINETUNE, TABS.FILTERS, TABS.ADJUST, TABS.ANNOTATE, /*TABS.WATERMARK*/], // or ['Adjust', 'Annotate', 'Watermark']
        defaultTabId: TABS.FINETUNE, // or 'Annotate'
        defaultToolId: TOOLS.TEXT, // or 'Text'
    };



    // Assuming we have a div with id="editor_container"
    const filerobotImageEditor = new FilerobotImageEditor(
        target,
        config,
    );

    filerobotImageEditor.render({

    });
    return filerobotImageEditor;
}

export class LiveEditImageDialog extends BaseComponent {
    constructor() {
        super();


    }
    editImage(url, callback) {

        setTimeout(() => {
            // Simulating an asynchronous operation with setTimeout
            this.editImageUrl(url).then(result => {
                callback(result);
            });
        }, 300);

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

                mw.top().app.registerChange(this);
                var _img = new Image();
                const currentImgData = imageEditor.getCurrentImgData();
                _img.src = currentImgData.imageData.imageBase64;

                const adjustments = currentImgData.designState.adjustments;

                const adjustmentsData = {
                    crop: !!adjustments.crop.width || !!adjustments.crop.height,
                    isFlippedX: adjustments.isFlippedX,
                    isFlippedY: adjustments.isFlippedY,
                    rotated: adjustments.rotation !== 0,
                };

                let sizeChanged = false;
                for(let key in adjustmentsData) {
                    if(adjustmentsData[key] === true){
                        sizeChanged = true
                        break;
                    }
                }

                mw.top().app.normalizeBase64Image(_img, function () {

                    resolve({
                        src: this.src,
                        sizeChanged,
                        sizeAdjustments: adjustmentsData
                    });
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
