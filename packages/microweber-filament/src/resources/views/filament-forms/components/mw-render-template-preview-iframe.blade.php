@props([ 'url' => '','lazyLoad' => false,'activeSiteTemplateInputName'=> 'active_site_template','layoutFileInputName'=> 'layout_file'])

<div>
    @php




        $active_site_template = template_name();
        $layout_file = 'clean.blade.php';

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

            $layout = app()->layouts_manager->get_layout_details($layout_options);

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


    <div x-data="{
        tplPreview: null,
        currentUrlHash: '{!! md5($url) !!}',
        lastInitializedHash: '',
        init() {
            // Only initialize if URL hash has changed or never initialized
            if (this.currentUrlHash !== this.lastInitializedHash) {
                if (this.tplPreview) {
                    // Cleanup existing instance if it exists
                    this.tplPreview = null;
                }

                this.tplPreview = new mw.templatePreview({
                    element: '#preview_frame_container_holder'
                });

                this.lastInitializedHash = this.currentUrlHash;

                @if($url != '')
                this.tplPreview.rend('{!! $url !!}');
                @endif
            }

            Livewire.on('dynamicPreviewLayoutChange', (data) => {
                if (data && data.iframePreviewUrl && this.tplPreview) {
                    this.tplPreview.rend(data.iframePreviewUrl);
                }
            });

            Livewire.on('reloadIframePreview', (data) => {
                if (this.tplPreview) {
                    this.tplPreview.rend('{!! $url !!}');
                }
            });
        }
    }">




      <div>

          <div class="preview_frame_wrapper preview_frame_wrapper_holder loading left">
              {{-- task-2026-05-05-636530 (Audit-#11) — leftover dev
                   placeholder "Preview Frame Container" was visible
                   to admin users on the template-customization page
                   per Drunk-Designer external audit. Removed the
                   visible string; the iframe is injected here at
                   runtime and the empty container is fine when
                   no preview is loaded. --}}
              <div class="preview_frame_container preview_frame_container_holder" id="preview_frame_container_holder">
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
    </div>
</div>
