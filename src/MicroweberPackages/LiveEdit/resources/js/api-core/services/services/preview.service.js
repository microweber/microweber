import  CSSGUIService from "../../../api-core/services/services/css-gui.service.js";


let isEditMode = mw.cookie.get('isEditMode');
if(isEditMode === undefined) {
    mw.cookie.set('isEditMode', true);
}

isEditMode = mw.cookie.get('isEditMode') === 'true';





const _prepareCss = () => {
    var body = mw.app.canvas.getDocument().body;
    if (!body.__hasPreviewcss) {
        body.__hasPreviewcss = true;
        var css = `
                html.mw-le--page-preview body{
                    padding-top: 0 !important
                }
                html.mw-le--page-preview .mw-le-spacer,
                html.mw-le--page-preview .mw-le-spacer *,
                html.mw-le--page-preview .mw-le-spacer * *,
                html.mw-le--page-preview .mw-le-resizable{

                    opacity:0 !important;
                    pointer-events: none !important;
                }


                html.mw-le--page-preview .mw_image_resizer,
                html.mw-le--page-preview #live_edit_toolbar_holder,
                html.mw-le--page-preview .mw-handle-item,
                html.mw-le--page-preview .mw-selector,
                html.mw-le--page-preview .mw_dropable,
                html.mw-le--page-preview .mw-padding-ctrl,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-cloneable-control,
                html.mw-le--page-preview .mw-bg-image-handles,
                html.mw-le--page-preview .mw-small-editor,
                html.mw-le--page-preview #live_edit_toolbar_holder
                {
                    display: none !important
                }
            `

        const node = mw.app.canvas.getDocument().createElement('style');
        node.textContent = css;
        body.appendChild(node);

    }
}


export const previewMode = function () {
    document.documentElement.classList.add('preview');

    document.documentElement.style.setProperty('--toolbar-height', '0px');
    mw.app.canvas.getDocument().documentElement.classList.add('mw-le--page-preview');
    mw.app.canvas.getDocument().body.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = 'inherit');

    CSSGUIService.hide();

    document.querySelector('#user-menu-wrapper').classList.remove('active');

    mw.cookie.set('isEditMode', false);

    _prepareCss()

}

export const liveEditMode = function () {
    document.documentElement.classList.remove('preview');
    document.documentElement.style.setProperty('--toolbar-height', document.documentElement.style.getPropertyValue('--toolbar-static-height'));
    mw.app.canvas.getDocument().documentElement.classList.remove('mw-le--page-preview');
    mw.app.canvas.getDocument().body.classList.add('mw-live-edit');
    mw.cookie.set('isEditMode', true);
    _prepareCss()

}

mw.app.isPreview = () => {
    return mw.cookie.get('isEditMode') === 'false';
}





export const pagePreviewToggle = function () {
    isEditMode = !isEditMode;
    if (!isEditMode) {
        previewMode();
    } else {
        liveEditMode()
    }




}
