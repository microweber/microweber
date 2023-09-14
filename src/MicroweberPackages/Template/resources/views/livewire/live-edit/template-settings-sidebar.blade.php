@extends('admin::layouts.iframe')

@section('content')
<div>

    <div wire:ignore>
        <style>
            body {
                background: #f6f8fb !important;
                padding-left:15px;
                padding-top:10px;
            }
        </style>

        <script>
            document.addEventListener('mw-option-saved', function() {
                mw.top().app.templateSettings.reloadStylesheet('{{$styleSheetSourceFile}}', '{{$optionGroupLess}}');
            });
        </script>
    </div>

<div>
    @foreach($settingsGroups as $settingGroupName=>$settingGroup)
    <div wire:key="setting-group-key-{{md5($settingGroupName)}}" class="mt-2">
        <div>
            <a class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                {{$settingGroupName}}
            </a>
        </div>

        <div>
            @foreach($settingGroup['values'] as $settingName=>$settingFields)
                <div wire:key="setting-values-key-{{md5($settingName)}}">
                 <div class="mt-2">
                    <a class="mw-admin-action-links">
                        <b>{{$settingName}}</b>
                    </a>
                </div>
                <div>
                    @foreach($settingFields as $settingFieldKey=>$settingField)

                        <div wire:key="setting-field-key-{{md5($settingFieldKey)}}">
                        @php
                        $settingFieldOptionGroup = $settingField['optionGroup'];
                        @endphp

                        @if($settingField['type'] == 'font_selector')

                            <div class="mt-3">
                                <label class="live-edit-label">
                                    {{$settingField['label']}}
                                </label>
                                <livewire:microweber-option::font-picker label="{{$settingField['label']}}"
                                                                         :optionKey="$settingFieldKey"
                                                                         :optionGroup="$settingFieldOptionGroup"
                                />
                            </div>

                        @elseif($settingField['type'] == 'color')
                            <div>
                                <livewire:microweber-option::color-picker
                                    label="{{$settingField['label']}}"
                                    :optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                            </div>
                        @elseif($settingField['type'] == 'dropdown')
                            <div class="mt-4 mb-3">
                                <label class="live-edit-label">
                                    {{$settingField['label']}} <br />
                                    @if(isset($settingField['help']))
                                        {{$settingField['help']}}
                                    @endif
                                </label>
                                @php
                                    $dropdownOptions = $settingField['options'];
                                @endphp
                                <livewire:microweber-option::dropdown :dropdownOptions="$dropdownOptions" optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                            </div>
                        @else
                        <div>
                            <label class="live-edit-label">
                                {{$settingField['label']}}
                            </label>
                            <livewire:microweber-option::text :optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                        </div>
                        @endif

                        </div>
                    @endforeach
                </div>
                </div>
            @endforeach
        </div>

    </div>
    @endforeach

</div>
</div>
@endsection
