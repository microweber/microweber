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
        <div>
            @foreach($styleSettings as $styleSetting)

                <div style="border:1px solid #000;margin-top:25px;">

                    <div>
                        @if (isset($styleSetting['title']))
                            <a class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                                {{ $styleSetting['title'] }}
                            </a>
                        @endif
                        @if(isset($styleSetting['description']))
                            <p>{{$styleSetting['description']}}</p>
                        @endif
                    </div>

                    <div class="mt-3">
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
