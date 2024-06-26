
<div x-data="{
currentLanguageData: @js($currentLanguageData)
}"
     x-init="function() {
mw.on('mlChangedLanguage', function (e, mlCurrentLanguage) {
currentLanguageData = mlCurrentLanguage;
});
}">

    <div class="input-group input-group-top-bottom-left-radius">

        @foreach($supportedLanguages as $language)

            @php
                if($language['locale'] == $defaultLanguage) {
                    $wireStateKey = 'state.settings.' . $fieldName;
                } else {
                    $wireStateKey = 'state.translations.'.$language['locale'].'.'. $fieldName;
                }
            @endphp

            <div x-show="currentLanguageData.locale == '{{$language['locale']}}'" wire:ignore>
                 <x-microweber-ui::link-picker :wire:model.debounce.500ms="$wireStateKey" />
            </div>

        @endforeach


        <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split gap-2" aria-expanded="false">
            <i :class="function () {
            return 'flag-icon flag-icon-'+currentLanguageData.icon+' me-2';
    }"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-end">
            @foreach($supportedLanguages as $language)
                <a class="dropdown-item" href="#" x-on:click="function() {
                currentLanguageData = @js($language);
                mw.trigger('mlChangedLanguage', currentLanguageData);
        }">
                    <i class="flag-icon flag-icon-{{$language['icon']}} me-2"></i>
                    <span> {{strtoupper($language['locale'])}}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
