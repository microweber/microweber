<div>

    <?php

    $fieldName = $this->optionName;
    $currentLanguageData = [];
    ?>


    <div>
        <div class="input-group">

            @foreach($supportedLanguages as $language)

                <input

                    @if($language['locale'] == $defaultLanguage)

                        wire:model.debounce.100ms="state.settings.{{$fieldName}}"
                    @else

                        wire:model.debounce.100ms="state.translations.{{$language['locale']}}.{{$fieldName}}"
                    style="xxdisplay:none"
                    @endif

                    :dir="function() {
                    return mw.admin.rtlDetect.getLangDir('{{$language['locale']}}')
                }"
                    lang="{{$language['locale']}}"

                    type="text" class="form-control">
            @endforeach

            <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split"
                    aria-expanded="false">
                <i :class="function () {
                    return 'flag-icon flag-icon-'+currentLanguageData.icon+' mr-4';
            }"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end">
                @foreach($supportedLanguages as $language)

                @endforeach
            </div>
        </div>
    </div>
</div>
