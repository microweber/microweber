@extends('admin::layouts.iframe')

@section('content')
<div>

    <style>
        body {
            background: #f6f8fb !important;
            padding-left:15px;
            padding-top:10px;
        }
    </style>

    @foreach($settingsGroups as $settingGroupName=>$settingGroup)
    <div class="mt-2">
        <div>
            <a class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                {{$settingGroupName}}
            </a>
        </div>

        <div>
            @foreach($settingGroup['values'] as $settingName=>$settingFields)
                <div class="mt-2">
                    <a class="mw-admin-action-links">
                        <b>{{$settingName}}</b>
                    </a>
                </div>
                <div>
                    @foreach($settingFields as $settingFieldKey=>$settingField)

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

                        @else
                        <div>
                            <label class="live-edit-label">
                                {{$settingField['label']}}
                            </label>
                            <livewire:microweber-option::text :optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                        </div>
                        @endif

                    @endforeach
                </div>
            @endforeach
        </div>

    </div>
    @endforeach

</div>
@endsection
