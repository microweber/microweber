<div>
    <div class="input-group">
        <input type="text" class="form-control">
        <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split" aria-expanded="false">
            <i class="flag-icon flag-icon-{{get_flag_icon($currentLanguage)}} mr-4"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            @foreach($supportedLanguages as $language)
            <a class="dropdown-item" href="#">
                <i class="flag-icon flag-icon-{{$language['icon']}} mr-4"></i>
                <span> {{strtoupper($language['locale'])}}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
