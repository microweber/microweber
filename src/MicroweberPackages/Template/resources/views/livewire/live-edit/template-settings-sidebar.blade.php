@extends('admin::layouts.iframe')

@section('content')

<div wire:ignore>
    <style>
        body {
            background: #f6f8fb !important;
            padding-left:15px;
            padding-top:10px;
        }
    </style>

    @if(isset($styleSettings))
        <div x-data="{showStyleSettings: 'all'}">

            @foreach($styleSettings as $styleSetting)

                @php
                    $hash = md5(json_encode($styleSetting));
                @endphp

                <div x-show="showStyleSettings == 'all' || showStyleSettings == '{{ $hash }}'" class="mt-3">

                    <div x-show="showStyleSettings !== '{{ $hash }}'">
                        @if (isset($styleSetting['title']))
                            <a x-on:click="showStyleSettings = '{{ $hash }}'" class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                                {{ $styleSetting['title'] }}
                            </a>
                        @endif
                    </div>


                    <div x-show="showStyleSettings == '{{ $hash }}'" x-transition:enter="tab-pane-slide-left-active" class="mt-3">

                        <div>
                            <button x-on:click="showStyleSettings = 'all'" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" type="button">
                                <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                                <div class="ms-1 font-weight-bold">
                                    Back to main
                                </div>
                            </button>
                        </div>

                        <div>
                            @if(isset($styleSetting['title']))
                                <h4>{{$styleSetting['title']}}</h4>
                            @endif
                            @if(isset($styleSetting['description']))
                                <p>{{$styleSetting['description']}}</p>
                            @endif
                        </div>


                        <div>
                            @if(isset($styleSetting['settings']))
                                @include('template::livewire.live-edit.template-setting-item', ['item' => $styleSetting])
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    @endif



    <br />
    <br />
    <br />
    <br />

    <script>
        document.addEventListener('livewire:load', function () {

            var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_rte_css_editor2_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.disable_auto_element_change = true;
            attrsForSettings.output_static_selector = true;
            attrsForSettings.from_url = mw.top().app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            $('#iframe-holder').html('<iframe src="'+src+'" style="width:100%;height:500px;border:none;"></iframe>');
        });


        mw.selectCssEditorElement = function (el){
            var val = $(el).val();

            mw.top().app.dispatch('cssEditorSelectElementBySelector', val);

        }

    </script>

    <select onchange="mw.selectCssEditorElement(this)">
        <option value=".main">Main</option>
        <option value="body">body</option>
        <option value="h1">h1</option>
        <option value="h2">h2</option>
        <option value="h3">h3</option>
        <option value=".form-control">.form-control</option>
        <option value=".btn.btn-primary">.btn.btn-primary</option>
        <option value=".col-6">.col-6</option>
        <option value=".btn.btn-link">.btn.btn-link</option>
        <option value="aaaaaaa">aaaaaaa</option>
        <option value="aaaaaaa.btn-link">aaaaaaa.btn-link</option>
        <option value="aaaaaaa#test">aaaaaaa#test</option>
    </select>

    <div>
        <div id="iframe-holder">


        </div>


    </div>

</div>

</div>
@endsection
