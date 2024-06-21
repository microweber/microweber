@props([ 'lazyLoad' => false ])

<div>
    @php






        $active_site_template = template_name();
        $layout_file = 'clean.php';

        $transformScale = 0.553;

            if(isset($this->data['active_site_template'])){
                $active_site_template =   $this->data['active_site_template'];
            }
            if(isset( $this->data['layout_file'])){
                $layout_file =   $this->data['layout_file'];
            }


            $layout_options = array();


            $layout_options['active_site_template'] = $active_site_template;
            $layout_options['layout_file'] = $layout_file;
            $layout_options['no_cache'] = true;
            $layout_options['no_folder_sort'] = true;

            $layout = mw()->layouts_manager->get_layout_details($layout_options);
            $url = '';


            if(isset($layout['content_type']) and $layout['content_type'] == 'dynamic'){
                $isDynamic = true;
                $isShop = false;
            }

            if(isset($layout['layout_file_preview_url']) and $layout['layout_file_preview_url'] ){
                 $url = $layout['layout_file_preview_url'];
            }

            $rand = md5($url);
    @endphp




    <div>

        <div class="preview_frame_wrapper preview_frame_wrapper_holder loading left" wire:ignore>
            <div class="preview_frame_container preview_frame_container_holder">

aaaaaaaaaaaaaaa
            </div>

            <div class="card placeholder-glow mw-add-post-placeholder-loading">
                <div class="ratio ratio-21x9 card-img-top placeholder"></div>
                <div class="card-body">
                    <div class="placeholder col-9 mb-3"></div>
                    <div class="placeholder placeholder-xs col-10"></div>
                    <div class="placeholder placeholder-xs col-11"></div>
                    <div class="mt-3">
                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4"
                           aria-hidden="true"></a>
                    </div>
                </div>
            </div>

        </div>


        <style>
            .preview_frame_container {
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

            .preview_frame_wrapper {
                position: relative;
            }

            .preview_frame_wrapper .mw-spinner {
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

            .preview_frame_wrapper.has-mw-spinner iframe {
                opacity: 0;
            }


        </style>


    </div>


    @script


    <script>


        document.addEventListener('livewire:initialized', () => {

            Livewire.on('dynamicPreviewLayoutChange', (data) => {

                if (data && data.iframePreviewUrl) {
                    mw.admin.filament.helpers.templatePreviewHelper().rend(data.iframePreviewUrl)
                }


            });
            @if($url)


            mw.admin.filament.helpers.templatePreviewHelper().rend('{!! $url !!}')
            @endif



        });


    </script>


    @endscript
</div>
