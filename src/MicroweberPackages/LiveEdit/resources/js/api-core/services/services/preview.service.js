let isPreview = true;
let _hascss;


export const previewMode = function () {
    document.documentElement.classList.add('preview');
    document.documentElement.style.setProperty('--toolbar-height', '0px');
    frame.contentWindow.document.documentElement.classList.add('mw-le--page-preview');
    frame.contentWindow.document.body.classList.remove('mw-live-edit');
    document.querySelector('#css-editor-template').classList.remove('active');
    document.querySelector('#bubble-nav').classList.remove('active');
    document.querySelector('#user-menu-wrapper').classList.remove('active');
}

export const liveEditMode = function () {
    document.documentElement.classList.remove('preview');
    document.documentElement.style.setProperty('--toolbar-height', document.documentElement.style.getPropertyValue('--toolbar-static-height'));
    frame.contentWindow.document.documentElement.classList.remove('mw-le--page-preview');
    frame.contentWindow.document.body.classList.add('mw-live-edit');
    document.querySelector('#bubble-nav').classList.add('active');
}
export const pagePreviewToggle = function () {
    isPreview = !isPreview;
    if (!isPreview) {
        previewMode();
    } else {
        liveEditMode()
    }


    if (!_hascss) {
        _hascss = true;
        var css = `
                html.mw-le--page-preview body{
                    padding-top: 0 !important
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
                html.mw-le--page-preview #live_edit_toolbar_holder
                {
                    display: none !important
                }
            `

        const node = frame.contentWindow.document.createElement('style');
        node.textContent = css;
        frame.contentWindow.document.body.appendChild(node);
    }

}
