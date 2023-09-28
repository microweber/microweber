<div class="mt-3">

    @if(isset($setting['title']))
        <div>
            @if(isset($setting['settings']))
                <a href="#" x-on:click="showStyleSettings = '{{$setting['url']}}'"
                   class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{$setting['title']}}
                </a>
            @endif
        </div>
    @endif

    <div>
        @if(isset($setting['fieldType']))

            @if ($setting['fieldType'] == 'styleEditor')
                <a href="#"
                   x-on:click="() => {
                        showStyleSettings = 'styleEditor';
                        styleEditorData = {{json_encode($setting)}};
                    }"

                   class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{$setting['title']}}
                </a>
            @endif

            @if ($setting['fieldType'] == 'infoBox')
                <b>{{$setting['title']}}</b>
                <p>{{$setting['description']}}</p>
            @endif

            @if ($setting['fieldType'] == 'fontFamily')
                <x-microweber-ui::font-picker x-on:change="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('body', 'fontFamily', event.target.value);
                }" />
            @endif

            @if ($setting['fieldType'] == 'fontSize')
                <x-microweber-ui::range-slider x-on:update="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('body', 'fontSize', event.target.value + 'px);
                }"
               label="{{$setting['title']}}" min="8" max="120" labelUnit="px" />
            @endif
        @endif
    </div>
</div>
