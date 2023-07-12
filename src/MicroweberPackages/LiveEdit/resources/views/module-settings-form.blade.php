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

                @php

                    $type = $formItem['type'] ?? 'text';
                    $placeholder = $formItem['placeholder'] ?? '';
                    $label = $formItem['label'] ?? false;
                    $help = $formItem['help'] ?? '';
                    $required = $formItem['required'] ?? false;
                    $autocomplete = $formItem['autocomplete'] ?? false;
                    $options = $formItem['options'] ?? false;
                    $select = $formItem['select'] ?? false;
                    $settingsKey = 'settings.' . $formItemKey;

                    $fieldType = $type;


                    $element = $formBuilder->make($fieldType, $settingsKey,$this->moduleId);
                     $element->setAttribute('wire:model.debounce.100ms', $settingsKey);
                    $element->setAttribute('module', $this->moduleType);

                    if ($label and method_exists($element, 'label')) {
                        $element->label($label);
                    }
                    if ($placeholder and method_exists($element, 'placeholder')) {
                        $element->placeholder($placeholder);
                    }
                    if ($autocomplete and method_exists($element, 'autocomplete')) {
                        $element->autocomplete($autocomplete);
                    }
                    if ($required and method_exists($element, 'required')) {
                        $element->required($required);
                    }
                    if ($options and method_exists($element, 'options')) {
                        $element->options($options);
                    }
                    if ($select and method_exists($element, 'select')) {
                        $element->select($select);
                    }

                    print $element->render();
                @endphp

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
