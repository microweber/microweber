<div>

    @if($customField->type =='text')
        <div class="mt-3">
            <x-microweber-ui::label for="as_text_area" value="Use as textarea" />
            <x-microweber-ui::toggle id="as_text_area" class="mt-1 block w-full" wire:model="state.options.as_text_area" />
        </div>
    @endif

    @if($customField->type == 'checkbox' || $customField->type == 'dropdown' || $customField->type == 'radio')

        @if(\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled())

            @php
                $supportedLanguages = \MicroweberPackages\Multilanguage\MultilanguageHelpers::getSupportedLanguages();
                $currentLang = mw()->lang_helper->current_lang();
                $currentLanguageData = [];
                foreach ($supportedLanguages as $supportedLanguage) {
                    if ($supportedLanguage['locale'] == $currentLang) {
                        $currentLanguageData = $supportedLanguage;
                    }
                }
            @endphp

            <div
                x-data="{
                    currentLanguageData: @js($currentLanguageData)
                    }"
                        x-init="function() {
                    mw.on('mlChangedLanguage', function (e, mlCurrentLanguage) {
                        console.log(mlCurrentLanguage);
                        currentLanguageData = mlCurrentLanguage;
                    });
                    }"
                class="mt-2">
                <label class="live-edit-label" for="type">
                  Values
                </label>

                <div class="btn-group w-100" role="group">
                @foreach($supportedLanguages as $supportedLanguage)

                        <input
                            x-on:click="function() {
                    currentLanguageData = @js($supportedLanguage);
                    mw.trigger('mlChangedLanguage', currentLanguageData);
            }"
                            @if($supportedLanguage['locale'] == $currentLang)
                                checked="checked"
                            @endif

                            type="radio" class="btn-check" name="btn-radio-toolbar" id="btn-radio-toolbar-02-{{$supportedLanguage['locale']}}" autocomplete="off">
                        <label for="btn-radio-toolbar-02-{{$supportedLanguage['locale']}}" class="btn btn-icon">
                            <i class="flag-icon flag-icon-{{$supportedLanguage['icon']}} mr-4"></i>
                            <span>   &nbsp;  {{strtoupper($supportedLanguage['locale'])}}</span>
                        </label>
                @endforeach
                </div>
                <div class="mt-2 bg-white px-2 py-2">
                    @foreach($supportedLanguages as $supportedLanguage)
                       <div
                           x-show="currentLanguageData.locale == '{{$supportedLanguage['locale']}}'"> 
                           @include('custom_field::livewire.custom-field-values-multivalues', ['stateKey'=>'multivaluesMl.'.$supportedLanguage['locale']])
                       </div>
                    @endforeach
                </div>
            </div>

        @else
          @include('custom_field::livewire.custom-field-values-multivalues', ['stateKey'=>'multivalues'])
        @endif

    @elseif($customField->type == 'price')

    <div class="mt-1">
        <x-microweber-ui::label for="price" value="Price" />
        <x-microweber-ui::input-price id="price" wire:model.defer="state.value" />
    </div>

    @elseif($customField->type == 'property')
        <div class="mt-3">
            <x-microweber-ml::input-textarea label-text="Value" wire-model-name="value" wire-model-defer="1" />
        </div>
    @elseif (isset($customField->options['as_text_area']) && $customField->options['as_text_area'])

        <div>
            <div class="mt-3">
                <x-microweber-ml::input-textarea label-text="Value" wire-model-name="value" wire-model-defer="1" />
            </div>

            <div class="d-flex gap-3 mt-3">
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_rows" value="Textarea Rows" />
                    <x-microweber-ui::input id="textarea_rows" class="mt-1 block w-full" wire:model="state.options.rows" />
                </div>
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_cols" value="Textarea Cols" />
                    <x-microweber-ui::input id="textarea_cols" class="mt-1 block w-full" wire:model="state.options.cols" />
                </div>
            </div>
        </div>

    @else
        <div class="mt-3">
            <x-microweber-ml::input-text label-text="Value" wire-model-name="value" wire-model-defer="1" />
        </div>
    @endif


</div>
