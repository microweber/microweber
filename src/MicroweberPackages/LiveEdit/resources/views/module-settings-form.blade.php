<div>

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

            <div class="form-group">

                @if(isset($formItem['label']) and $formItem['label'])
                <label class="form-label"><?php print $formItem['label']; ?></label>
                @endif


                    <?php
                    $type = $formItem['type'] ?? 'text';
                    $placeholder = $formItem['placeholder'] ?? '';
                    $help = $formItem['help'] ?? '';
                    $required = $formItem['required'] ?? false;
                    $settingsKey = 'settings.' . $formItemKey;

                    echo $formBuilder->text($settingsKey)
                        ->label($formItem['label'])
                        ->class('form-control')
                        ->setAttribute('wire:model.debounce.100ms', $settingsKey)
                        ->autocomplete(false);


                    ?>


{{--
                <input type="text" placeholder="{{$placeholder}}" class="form-control" wire:model.debounce.100ms="{{$settingsKey}}"/>
--}}
                    @if(isset($formItem['help']) and $formItem['help'])
                    <small class="form-hint">{{ $formItem['help'] }}</small>
                    @endif

            </div>





        @endforeach


    @endif

</div>
