@php
    $rand = md5($url);
@endphp

<div class="preview_frame_wrapper preview_frame_wrapper_{{$rand}} loading left">
    <div class="preview_frame_container preview_frame_container_{{$rand}}"></div>
</div>

<style>
    .preview_frame_container{
        position: relative;
        overflow: hidden;
    }
    .preview_frame_container.preview-in-self {
        height: calc(50vh - 80px);

    }
    .preview_frame_container.preview-in-self iframe {
        height: calc(200vh - 160px) !important;
    }

    .preview_frame_container.preview-in-iframe {
        height: 800px;

    }
    .preview_frame_container.preview-in-iframe iframe {
        height: 1600px !important;
    }
    .preview_frame_wrapper{
        position: relative;
    }
    .preview_frame_wrapper .mw-spinner{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .preview_frame_container iframe {
        width: 400%;
        transform: scale(.25); 
        top: 0;
        position: absolute;
        left: 0;
        transform-origin: 0 0;
        border: 1px solid silver;
        transition: .3s;
    }
    .preview_frame_wrapper.has-mw-spinner iframe{
        opacity: 0;
    }


</style>

<script>
    mw.templatePreview{{$rand}} = {
        set: function () {
            var iframe = document.querySelector('.preview_frame_wrapper_{{$rand}} iframe');
            var framewindow = iframe.contentWindow;
            framewindow.scrollTo(0, 0);
        },
        rend: function (url) {

            var holder = mw.$('.preview_frame_container_{{$rand}}');
            var wrapper = mw.$('.preview_frame_wrapper_{{$rand}}');

            if (self !== top ) {
                holder.addClass('preview-in-iframe');
            } else {
                holder.addClass('preview-in-self')
            }

            var frame = document.createElement('iframe');
            frame.src = url;
            frame.className = 'preview_frame_small';
            frame.tabIndex = -1;
            frame.frameborder = 0;
            frame.onload = function (ev) {
                mw.templatePreview<?php print $rand; ?>.set();
                this.contentWindow.document.documentElement.className = 'mw-template-document-preview';
                mw.spinner({
                    element: '.preview_frame_wrapper_{{$rand}}',
                }).hide()
            };
            holder.empty();
            mw.spinner({
                element: '.preview_frame_wrapper_{{$rand}}',
                size: 40
            }).show()
            holder.append(frame);
        },
        zoom: function (a) {
            if (typeof a == 'undefined') {
                var holder = mw.$('.preview_frame_wrapper_{{$rand}}');
                holder.toggleClass('zoom');
                if (holder[0] != null) {
                    var iframe = holder[0].querySelector('iframe');
                    if (iframe != null) {
                        iframe.contentWindow.scrollTo(0, 0);
                    }
                }
            }
            else if (a == 'out') {
                mw.$('.preview_frame_wrapper_{{$rand}}').removeClass('zoom');
            }
            else {
                mw.$('.preview_frame_wrapper_{{$rand}}').addClass('zoom');
            }
            mw.$('.preview_frame_wrapper_{{$rand}} iframe')[0].contentWindow.scrollTo(0, 0);
        }
    }
    mw.templatePreview{{$rand}}.rend('{!! $url !!}');
   // mw.templatePreview{{$rand}}.zoom();
</script>
