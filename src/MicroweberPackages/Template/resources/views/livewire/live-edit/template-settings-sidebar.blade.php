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

<div x-data="{showTab: 'main'}">

    @foreach($settingsGroups as $settingGroupName=>$settingGroup)
    <div wire:key="setting-group-key-{{md5($settingGroupName)}}" class="mt-2">

        <div x-show="showTab == 'main'">
            <a class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                {{$settingGroupName}}
            </a>
        </div>

        <div>
            @foreach($settingGroup['values'] as $settingName=>$settingFields)
                <div wire:key="setting-values-key-{{md5($settingName)}}">

                    <div x-show="showTab == 'setting-values-key-{{md5($settingName)}}'">
                        <button x-on:click="showTab = 'main'" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" type="button">
                            <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                            <div class="ms-1 font-weight-bold">
                                Back to {{mb_strtolower($settingGroupName)}}
                            </div>
                        </button>
                    </div>

                     <div x-show="showTab == 'main'" class="mt-2">
                        <a x-on:click="showTab = 'setting-values-key-{{md5($settingName)}}'" class="mw-admin-action-links">
                            <b>{{$settingName}}</b>
                        </a>
                    </div>

                    <div x-show="showTab == 'setting-values-key-{{md5($settingName)}}'" x-transition:enter="tab-pane-slide-right-active">
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
                                <div class="mt-3">
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
