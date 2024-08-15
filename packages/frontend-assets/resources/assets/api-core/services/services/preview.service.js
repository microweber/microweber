import  CSSGUIService from "../../../api-core/services/services/css-gui.service.js";








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
                html.mw-le--page-preview .mw-le-resizer,
                html.mw-le--page-preview .mw-layout-container > .mw-le-resizable,
                html.mw-le--page-preview .mw-handle-item {

                    opacity:0 !important;
                    pointer-events: none !important;
                }

                html.mw-le--page-preview .mw-handle-item * {

                    opacity:0 !important;
                    pointer-events: none !important;
                }


                html.mw-le--page-preview .moveable-control-box,
                html.mw-le--page-preview .mw_image_resizer,
                html.mw-le--page-preview #live_edit_toolbar_holder,
                html.mw-le--page-preview .mw-handle-item.mw-le-resizable,
                html.mw-le--page-preview .mw-layout-container > .mw-le-resizable,
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




const isEditMode = function (value) {

    if(value === undefined){
        return mw.top().app.isEditMode === undefined ? true : mw.top().app.isEditMode;
    }
    mw.top().app.isEditMode = value;

}


export const previewMode = function () {
    document.documentElement.classList.add('preview');

    document.documentElement.style.setProperty('--toolbar-height', '0px');

    mw.app.canvas.getDocument().documentElement.classList.add('mw-le--page-preview');
    mw.app.canvas.getDocument().body.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = 'inherit');

    CSSGUIService.hide();

    document.querySelector('#user-menu-wrapper').classList.remove('active');



    isEditMode(false)

    mw.app.dispatch('mw.previewMode');

    _prepareCss();

    mw.app.canvas.getDocument().body.querySelectorAll('.mw-free-layout-container').forEach(node => {
        const movable = node.__mvb;
        movable.draggable = false;
    });

}

export const liveEditMode = function () {
    document.documentElement.classList.remove('preview');
    document.documentElement.style.setProperty('--toolbar-height', document.documentElement.style.getPropertyValue('--toolbar-static-height'));
    mw.app.canvas.getDocument().documentElement.classList.remove('mw-le--page-preview');
    mw.app.canvas.getDocument().body.classList.add('mw-live-edit');
    isEditMode(true)
    mw.app.dispatch('mw.editMode');

    _prepareCss();
    mw.app.canvas.getDocument().body.querySelectorAll('.mw-free-layout-container').forEach(node => {
        const movable = node.__mvb;
        movable.draggable = true;
    });

}

mw.app.isPreview = () => {
    return isEditMode() === false;
}





export const pagePreviewToggle = function () {

    if (!mw.app.isPreview()) {
        previewMode();
    } else {
        liveEditMode()
    }




}
