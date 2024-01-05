<div x-data="{
defaultLanguageInputField: '{{$fieldValue}}',
currentLanguageData: @js($currentLanguageData)
}"
     x-init="function() {
mw.on('mlChangedLanguage', function (e, mlCurrentLanguage) {
    currentLanguageData = mlCurrentLanguage;
});
}"
>
    <textarea style="display:none" x-model="defaultLanguageInputField" name="{{$fieldName}}">{{$fieldValue}}</textarea>

    <div class="btn-group w-100" role="group">
        @foreach($supportedLanguages as $language)
            <input
                x-on:click="function() {
                currentLanguageData = @js($language);
                mw.trigger('mlChangedLanguage', currentLanguageData);
        }"
                @if($language['locale'] == $defaultLanguage)
                checked="checked"
                @endif

                type="radio" class="btn-check" name="btn-radio-toolbar" id="btn-radio-toolbar-{{$randId}}-{{$language['locale']}}" autocomplete="off">
            <label for="btn-radio-toolbar-{{$randId}}-{{$language['locale']}}" class="btn btn-icon">
                <i class="flag-icon flag-icon-{{$language['icon']}} mr-4"></i>
                <span> {{strtoupper($language['locale'])}}</span>
            </label>
        @endforeach
    </div>

    <div class="mt-2">
        @foreach($supportedLanguages as $language)
            <textarea
                name="multilanguage[{{$fieldName}}][{{$language['locale']}}]"
                wire:model.lazy="multilanguage[{{$fieldName}}][{{$language['locale']}}]"

                x-show="currentLanguageData.locale == '{{$language['locale']}}'"

                @if($language['locale'] == $defaultLanguage)
                x-model="defaultLanguageInputField"
                @else
                style="display:none"
                @endif

                :dir="function() {
                    return mw.admin.rtlDetect.getLangDir('{{$language['locale']}}')
                    }"
                lang="{{$language['locale']}}"

                class="form-control">{{$translations[$language['locale']]}}</textarea>
        @endforeach
    </div>
</div>
