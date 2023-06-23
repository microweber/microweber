<div x-data="{
currentLanguageData: @js($currentLanguageData)
}">
    <div class="input-group">

        @foreach($supportedLanguages as $language)
            <input name="multilanguage[{{$fieldName}}][{{$language['locale']}}]"
                   value="{{$translations[$language['locale']]}}"
                   x-show="currentLanguageData.locale == '{{$language['locale']}}'"
                   type="text" class="form-control">
        @endforeach

        <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split" aria-expanded="false">
            <i :class="function () {
                    return 'flag-icon flag-icon-'+currentLanguageData.icon+' mr-4';
            }"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-end">
            @foreach($supportedLanguages as $language)
            <a class="dropdown-item" href="#" x-on:click="currentLanguageData = @js($language)">
                <i class="flag-icon flag-icon-{{$language['icon']}} mr-4"></i>
                <span> {{strtoupper($language['locale'])}}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
