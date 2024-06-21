import BaseComponent from "../core/base-class.js";

export class AdminFilamentHelpers extends BaseComponent {
    constructor() {
        super();


    }


    templatePreviewHelper() {

        return mw.templatePreview = {
            set: function () {
                var iframe = document.querySelector('.preview_frame_wrapper iframe');
                var framewindow = iframe.contentWindow;
                framewindow.scrollTo(0, 0);
            },
            afterLoad: function () {
                mw.templatePreview.set();
                mw.$('.preview_frame_wrapper .mw-add-post-placeholder-loading').remove();
                mw.$('.preview_frame_container').show();
            },
            rend: function (url) {

                var holder = $('.preview_frame_container_holder');
                $('.preview_frame_container_holder iframe').remove();

                if (self !== top) {
                    holder.addClass('preview-in-iframe');
                } else {
                    holder.addClass('preview-in-self');
                }

                var frame = document.createElement('iframe');
                frame.src = url;
                frame.className = 'preview_frame_small';
                frame.id = 'preview_frame_wrapper_iframe';
                frame.tabIndex = -1;
                frame.frameborder = 0;
                frame.onload = function (ev) {
                    mw.templatePreview.afterLoad();
                    this.contentWindow.document.documentElement.className = 'mw-template-document-preview';


                };
               // holder.empty();
                holder.append(frame);
                return frame;
            },
            zoom: function (a) {
                if (typeof a == 'undefined') {
                    var holder = mw.$('.preview_frame_wrapper');
                    holder.toggleClass('zoom');
                    if (holder[0] != null) {
                        var iframe = holder[0].querySelector('iframe');
                        if (iframe != null) {
                            iframe.contentWindow.scrollTo(0, 0);
                        }
                    }
                } else if (a == 'out') {
                    mw.$('.preview_frame_wrapper').removeClass('zoom');
                } else {
                    mw.$('.preview_frame_wrapper').addClass('zoom');
                }
                mw.$('.preview_frame_wrapper iframe')[0].contentWindow.scrollTo(0, 0);
            }
        }
    }
}


export default AdminFilamentHelpers;
