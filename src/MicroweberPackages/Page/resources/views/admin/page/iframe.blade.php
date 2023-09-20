@props(['url', 'lazyLoad' => false ])
@php
    $rand = md5($url);
@endphp


<div class="preview_frame_wrapper preview_frame_wrapper_{{$rand}} loading left">
    <div class="preview_frame_container preview_frame_container_{{$rand}}"></div>

    <div class="card placeholder-glow mw-add-post-placeholder-loading">
        <div class="ratio ratio-21x9 card-img-top placeholder"></div>
        <div class="card-body">
            <div class="placeholder col-9 mb-3"></div>
            <div class="placeholder placeholder-xs col-10"></div>
            <div class="placeholder placeholder-xs col-11"></div>
            <div class="mt-3">
                <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a>
            </div>
        </div>
    </div>

</div>

<style>
    .preview_frame_container_{{$rand}} {
        display:none;
    }
</style>

<style>
    .preview_frame_container{
        position: relative;
        overflow: hidden;
    }
    .preview_frame_container.preview-in-self {
        @if(isset($iframeHeight))
        height: {{$iframeHeight}};
        @else
        height: calc(50vh - 80px);
        @endif
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
        @if (isset($iframeWidth))
        width: {{$iframeWidth}};
        @else
        width: 400%;
        @endif
        @if(isset($transformScale))
        transform: scale({{$transformScale}});
        @else
        transform: scale(0.25);
        @endif

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
        afterLoad: function () {
            mw.templatePreview<?php print $rand; ?>.set();
            mw.$('.preview_frame_wrapper_{{$rand}} .mw-add-post-placeholder-loading').remove();
            mw.$('.preview_frame_container_{{$rand}}').show();
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
            frame.id = 'preview_frame_wrapper_{{$rand}}_iframe';
            frame.tabIndex = -1;
            frame.frameborder = 0;
            frame.onload = function (ev) {
                mw.templatePreview<?php print $rand; ?>.afterLoad();
                this.contentWindow.document.documentElement.className = 'mw-template-document-preview';


            };
            holder.empty();
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
   // mw.templatePreview{{$rand}}.rend('{!! $url !!}');
   // mw.templatePreview{{$rand}}.zoom();
</script>
<script>

     @if(isset($lazyLoad) and $lazyLoad)
     $(window).on('load scroll', function () {
         if (mw.tools.inview(document.querySelector('.preview_frame_wrapper_{{$rand}}'))) {
             if(!document.getElementById('preview_frame_wrapper_{{$rand}}_iframe')){
                 mw.templatePreview{{$rand}}.rend('{!! $url !!}');
             }
         }
     });




     @else
     $(window).on('load', function () {
         if(!document.getElementById('preview_frame_wrapper_{{$rand}}_iframe')){
             mw.templatePreview{{$rand}}.rend('{!! $url !!}');
         }
     });
     @endif


</script>
