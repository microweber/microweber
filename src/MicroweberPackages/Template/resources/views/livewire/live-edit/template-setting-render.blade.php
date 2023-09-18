<div class="mt-3">

    @if(isset($setting['title']))
        <div>
            @if(isset($setting['settings']))
                <a href="#" x-on:click="showStyleSettings = '{{$setting['url']}}'"
                   class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{$setting['title']}}
                </a>
            @else
                <b>
                    {{$setting['title']}}
                </b>
            @endif
        </div>
    @endif

    <div>
        @if(isset($setting['fieldType']))

            @if ($setting['fieldType'] == 'styleEditor')
                styleEditor
            @endif

            @if ($setting['fieldType'] == 'infoBox')
                <p>{{$setting['description']}}</p>
            @endif

            @if ($setting['fieldType'] == 'fontFamily')
                <x-microweber-ui::font-picker />
            @endif

            @if ($setting['fieldType'] == 'fontSize')
                <x-microweber-ui::range-slider label="" min="8" max="120" labelUnit="px" />
            @endif
        @endif
    </div>
</div>
