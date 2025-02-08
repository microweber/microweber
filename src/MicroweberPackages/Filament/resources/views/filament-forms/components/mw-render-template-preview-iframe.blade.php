@props([ 'url' => '','lazyLoad' => false,'activeSiteTemplateInputName'=> 'active_site_template','layoutFileInputName'=> 'layout_file'])

<div>
    @php




        $active_site_template = template_name();
        $layout_file = 'clean.php';

        $transformScale = 0.553;

            if($activeSiteTemplateInputName and isset($this->data[$activeSiteTemplateInputName])){
                $active_site_template =   $this->data[$activeSiteTemplateInputName];
            } else if($activeSiteTemplateInputName and isset($this->$activeSiteTemplateInputName)){
                $active_site_template =   $this->$activeSiteTemplateInputName;
            }

//
//            if(isset( $this->data['layout_file'])){
//                $layout_file =   $this->data['layout_file'];
//            }
            if($layoutFileInputName and isset($this->data[$layoutFileInputName])){
            $layout_file =   $this->data[$layoutFileInputName];
            } else if($layoutFileInputName and isset($this->$layoutFileInputName)){
            $layout_file =   $this->$layoutFileInputName;

            }




            $layout_options = array();


            $layout_options['active_site_template'] = $active_site_template;
            $layout_options['layout_file'] = $layout_file;
            $layout_options['no_cache'] = true;
            $layout_options['no_folder_sort'] = true;

            $layout = mw()->layouts_manager->get_layout_details($layout_options);

            if(!isset($url)){
            $url = '';
            }

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
            <div class="preview_frame_container preview_frame_container_holder" id="preview_frame_container_holder">
                Preview Frame Container
            </div>

            <div class="card placeholder-glow mw-add-post-placeholder-loading">
                <div class="ratio ratio-21x9 card-img-top placeholder"></div>
                <div class="card-body">
                    <div class="placeholder col-9 mb-3"></div>
                    <div class="placeholder placeholder-xs col-10"></div>
                    <div class="placeholder placeholder-xs col-11"></div>

                </div>
            </div>

        </div>


    </div>


    <script>
        document.addEventListener('livewire:initialized', () => {

            let tplPreview = new mw.templatePreview({
                element: '#preview_frame_container_holder'
            });
            Livewire.on('dynamicPreviewLayoutChange', (data) => {
                if (data && data.iframePreviewUrl) {
                    tplPreview.rend(data.iframePreviewUrl)
                }

            });
            Livewire.on('reloadIframePreview', (data) => {
                tplPreview.rend('{!! $url !!}')
            });


            @if($url != '')

            tplPreview.rend('{!! $url !!}')

            @endif

        });
    </script>


</div>
