<div class="mt-3">

    <?php

    $rootSelector = false;



    if (isset($parent) and !empty($parent) and !empty($parent['rootSelector'])) {
        $rootSelector = trim($parent['rootSelector']);
    }

    ?>

    <?php
    $selectorToApply ='';
    if (isset($setting['selectors'])) {
        $selectorToApply = end($setting['selectors']);
        if ($rootSelector) {
            //   $selectorToApply = $rootSelector . '' . $selectorToApply;
            $selectorToApply = $rootSelector;
        }
    }

    ?>





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

            @if ($setting['fieldType'] == 'button')
                <div class="mt-2" style="background:#f7f7f7;border-radius:8px;padding: 12px;">
                    <div>
                        <b>{{$setting['title']}}</b>
                    </div>
                    <div class="mt-1">
                        <small>{{$setting['description']}}</small>
                    </div>
                    <button
                        x-on:click="(e) => {

                                 {{$setting['onClick']}}

                            }"
                        class="btn btn-outline-dark" style="width:100%;margin-top:15px">
                        &nbsp; {{$setting['title']}}
                    </button>
                </div>
            @endif




            @if ($setting['fieldType'] == 'clearAll')
                <div class="mt-2" style="background:#f7f7f7;border-radius:8px;padding: 12px;">
                    <div>
                        <b>{{$setting['title']}}</b>
                    </div>
                    <div class="mt-1">
                        <small>{{$setting['description']}}</small>
                    </div>


                    <button
                            x-on:click="(e) => {

                            mw.confirm('Are you sure you want to clear styles?',
                                function () {
                                    @foreach($setting['fieldSettings']['properties'] as $property)
                                    mw.top().app.cssEditor.setPropertyForSelector('{{ $selectorToApply }}', '{{$property}}', '');
                                    @endforeach
                                });

                            }"
                            class="btn btn-outline-dark" style="width:100%;margin-top:15px">
                        <i class="fa fa-trash"></i> &nbsp; {{$setting['title']}}
                    </button>
                </div>
            @endif


            @if ($setting['fieldType'] == 'colorPalette')


                    @include('template::livewire.live-edit.template-setting-render-color-palette-item', [
                            'setting' => $setting
                          ])





                    <?php

                    /* @if(isset($setting['fieldSettings']['colors']))
                     * @foreach($setting['fieldSettings']['colors'] as $colorPallete)
                     * <div class="mt-2">
                     * <div class="d-flex flex-cols gap-2"
                     *
                     * x-on:click="(e) => {
                     * @foreach($colorPallete['properties'] as $property=>$propertyValue)
                     * mw.top().app.cssEditor.setPropertyForSelector('{{$selectorToApply}}', '{{$property}}', '{{$propertyValue}}');
                     * @endforeach
                     * }"
                     *
                     * >
                     * @if(isset($colorPallete['name']))
                     *
                     * <div style="border-radius:6px;width:100%;height:40px;background:{{$colorPallete['name']}}"></div>
                     * @endif
                     *
                     * @foreach($colorPallete['mainColors'] as $mainColors)
                     * <div style="border-radius:6px;width:100%;height:40px;background:{{$mainColors}}"></div>
                     * @endforeach
                     * </div>
                     * </div>
                     * @endforeach
                     * @endif
                     */

                    ?>

            @endif

            @if ($setting['fieldType'] == 'colorPicker')

                <x-microweber-ui::color-picker
                        x-on:loaded="(colorPickerEvent) => {
                    let propertyValue = mw.top().app.cssEditor.getPropertyForSelector('{{ $selectorToApply }}', '{{$setting['fieldSettings']['property']}}');
                    colorPickerEvent.target.value = propertyValue;
                    mw.top().app.on('setPropertyForSelector', (propertyChangeEvent) => {
                         if (propertyChangeEvent.selector == '{{ $selectorToApply }}' && propertyChangeEvent.property == '{{$setting['fieldSettings']['property']}}') {

                             colorPickerEvent.target.value = propertyChangeEvent.value;
                             colorPickerEvent.target.dispatchEvent(new Event('input'));
                         }
                    });
                }"
                        x-on:update="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('{{ $selectorToApply }}', '{{$setting['fieldSettings']['property']}}', event.target.value);
                }"
                        label="{{$setting['title']}}"/>

            @endif

            @if ($setting['fieldType'] == 'rangeSlider')
                <x-microweber-ui::range-slider
                        x-on:loaded="(e) => {
                    let propertyValue = mw.top().app.cssEditor.getPropertyForSelector('{{$selectorToApply}}', '{{$setting['fieldSettings']['property']}}');
                    propertyValue = propertyValue.replace('{{$setting['fieldSettings']['unit']}}', '');
                    e.target.value = propertyValue;
                }"
                        x-on:slide="(e) => {
                    let currentPropertyValue = mw.top().app.cssEditor.getPropertyForSelector('{{$selectorToApply}}', '{{$setting['fieldSettings']['property']}}');
                    currentPropertyValue = currentPropertyValue.replace('{{$setting['fieldSettings']['unit']}}', '');
                    if (currentPropertyValue == event.target.value) {
                        return;
                    }
                    if (mw.top().app.cssEditor) {
                        mw.top().app.cssEditor.setPropertyForSelector('{{$selectorToApply}}', '{{$setting['fieldSettings']['property']}}', event.target.value + '{{$setting['fieldSettings']['unit']}}');
                    }
                }"
                        label="{{$setting['title']}}" min="{{$setting['fieldSettings']['min']}}"
                        max="{{$setting['fieldSettings']['max']}}" step="{{$setting['fieldSettings']['step']}}"
                        labelUnit="{{$setting['fieldSettings']['unit']}}"/>
            @endif

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
                <div class="mt-2">
                    <div>
                        <b>{{$setting['title']}}</b>
                    </div>
                    <div class="mb-2">
                        <small>{{$setting['description']}}</small>
                    </div>
                </div>
            @endif

            @if ($setting['fieldType'] == 'fontFamily')
                <x-microweber-ui::font-picker
                        x-on:loaded="(e) => {
                    setTimeout((et)=> {
                        let propertyValue = mw.top().app.cssEditor.getPropertyForSelector('{{$selectorToApply}}', '{{$setting['fieldSettings']['property']}}');
                        e.target.value = propertyValue;
                        e.target.dispatchEvent(new Event('input'));
                    }, 200);
                }"
                        x-on:input="(e) => {
                   if (mw.top().app.cssEditor) {
                        mw.top().app.cssEditor.setPropertyForSelector('{{$selectorToApply}}', '{{$setting['fieldSettings']['property']}}', event.target.value);
                    }
                }"/>
            @endif

        @endif
    </div>
</div>

