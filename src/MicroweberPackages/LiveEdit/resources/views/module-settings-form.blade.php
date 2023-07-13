<div id="mw-options-save-<?php print md5($this->moduleId) ?>">

    @if(isset($this->moduleTitle) and $this->moduleTitle)
        <h1 class="font-weight-bold mb-4"><?php print $this->moduleTitle; ?></h1>
    @endif


    @php

        /**
         * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
         */
        $formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);

    @endphp


    @if($this->settingsForm)

        @foreach($this->settingsForm as $formItemKey => $formItem)

            <div wire:ignore>

                <div class="d-flex justify-content-between">
                  <div>
                      <div><b>{{$formItem['label']}}</b></div>
                      <span class="text-muted">{{$formItem['help']}}</span>
                  </div>
                    <div>
                        @include('microweber-live-edit::render-input-form-element')
                    </div>
                </div>


            </div>

        @endforeach

    @endif


    <?php
    /*      <script>
              mw.require('options.js')
          </script>

          <script>
              document.addEventListener('livewire:load', function () {
                  mw.options.form('#mw-options-save-<?php print md5($this->moduleId) ?>', function () {
                      if (mw.notification) {
                          mw.notification.success('<?php _ejs('Settings are saved') ?>');
                      }
                  });
              });
          </script>*/
    ?>


</div>
