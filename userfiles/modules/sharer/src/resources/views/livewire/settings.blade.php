<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);

    $editorSettings = [
        'config' => [
            'title' => '',
            'addButtonText' => 'Add Item',
            'editButtonText' => 'Edit',
            'deleteButtonText' => 'Delete',
            'sortItems' => true,
            'settingsKey' => 'settings',
            'listColumns' => [
                'question' => 'Question',
            ],
        ],
        'schema' => [
            [
                'type' => 'text',
                'label' => 'Question',
                'name' => 'question',
                'placeholder' => 'Enter question',
                'help' => 'Enter Question',
            ],
            [
                'type' => 'textarea',
                'label' => 'Answer',
                'name' => 'answer',
                'placeholder' => 'Enter answer',
                'help' => 'Enter Answer',
                'maxlength' => '150'
            ]
        ]
    ];

    ?>


    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <button x-on:click="showMainEditTab = 'mainSettings'"
                        :class="{ 'active': showMainEditTab == 'mainSettings' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    @lang('Main settings')
                </button>
                <button x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    @lang('Design')
                </button>
            </div>
        </div>
    @endif

    <div x-show="showMainEditTab=='mainSettings'" x-transition:enter="tab-pane-slide-left-active">

        <div class="mt-3">
            <p>
                {{_e("Select socials networks you want to share")}}
            </p>
        </div>

        @foreach(getSharerSocialNetworks() as $socialNetwork=>$socialNetworkData)
            @php
                $socialNetworkOptionKeyEnable = $socialNetwork . '_enabled';
                $socialNetworkIsEnabled = get_option($socialNetworkOptionKeyEnable, $this->moduleId);
            @endphp
            <div class="form-check my-3">
                <div class="d-flex flex-wrap align-items-center">
                    <div @mw-option-saved.window="function() {
                            mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '{{$this->moduleId}}'} || {}))
                        }" class="d-flex col-xl-3 col-md-6 col-12">

                        <livewire:microweber-option::toggle value="y" :optionKey="$socialNetworkOptionKeyEnable" :optionGroup="$moduleId" module="social_links" />
                        <div>
                            <label class="form-check-label d-flex align-items-center" for="{{$socialNetworkOptionKeyEnable}}">
                           @if($socialNetwork=="viber")
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="25" width="25" version="1.1" id="Layer_1"
                                viewBox="-94.79835 -166.597 821.5857 999.582">
                                <defs id="defs178">
                                    <style id="style176">
                                        .cls-1 {
                                            fill: var(--mw-primary-color)
                                        }

                                        .cls-2 {
                                            fill: none;
                                            stroke: var(--mw-primary-color);
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-width: 16.86px
                                        }
                                    </style>
                                </defs>
                                <path id="path182"
                                    d="M560.651 64.998c-16.56-15.28-83.48-63.86-232.54-64.52 0 0-175.78-10.6-261.47 68-47.7 47.71-64.48 117.52-66.25 204.07-1.77 86.55-4.06 248.75 152.29 292.73h.15l-.1 67.11s-1 27.17 16.89 32.71c21.64 6.72 34.34-13.93 55-36.19 11.34-12.22 27-30.17 38.8-43.89 106.93 9 189.17-11.57 198.51-14.61 21.59-7 143.76-22.66 163.63-184.84 20.51-167.17-9.92-272.91-64.91-320.57zm18.12 308.58c-16.77 135.42-115.86 143.93-134.13 149.79-7.77 2.5-80 20.47-170.83 14.54 0 0-67.68 81.65-88.82 102.88-3.3 3.32-7.18 4.66-9.77 4-3.64-.89-4.64-5.2-4.6-11.5.06-9 .58-111.52.58-111.52s-.08 0 0 0c-132.26-36.72-124.55-174.77-123.05-247.06 1.5-72.29 15.08-131.51 55.42-171.34 72.48-65.65 221.79-55.84 221.79-55.84 126.09.55 186.51 38.52 200.52 51.24 46.52 39.83 70.22 135.14 52.89 274.77z"
                                    class="cls-1" />
                                <path id="path184" d="M389.471 268.768q-2.46-49.59-50.38-52.09" class="cls-2" />
                                <path id="path186" d="M432.721 283.268q1-46.2-27.37-77.2c-19-20.74-45.3-32.16-79.05-34.63" class="cls-2" />
                                <path id="path188" d="M477.001 300.588q-.61-80.17-47.91-126.28t-117.65-46.6" class="cls-2" />
                                <path id="path190"
                                    d="M340.761 381.678s11.85 1 18.23-6.86l12.44-15.65c6-7.76 20.48-12.71 34.66-4.81a366.67 366.67 0 0130.91 19.74c9.41 6.92 28.68 23 28.74 23 9.18 7.75 11.3 19.13 5.05 31.13 0 .07-.05.19-.05.25a129.81 129.81 0 01-25.89 31.88c-.12.06-.12.12-.23.18q-13.38 11.18-26.29 12.71a17.39 17.39 0 01-3.84.24 35 35 0 01-11.18-1.72l-.28-.41c-13.26-3.74-35.4-13.1-72.27-33.44a430.39 430.39 0 01-60.72-40.11 318.31 318.31 0 01-27.31-24.22l-.92-.92-.92-.92-.92-.92c-.31-.3-.61-.61-.92-.92a318.31 318.31 0 01-24.22-27.31 430.83 430.83 0 01-40.11-60.71c-20.34-36.88-29.7-59-33.44-72.28l-.41-.28a35 35 0 01-1.71-11.18 16.87 16.87 0 01.23-3.84q1.61-12.89 12.73-26.31c.06-.11.12-.11.18-.23a129.53 129.53 0 0131.88-25.88c.06 0 .18-.06.25-.06 12-6.25 23.38-4.13 31.12 5 .06.06 16.11 19.33 23 28.74a366.67 366.67 0 0119.74 30.94c7.9 14.17 2.95 28.68-4.81 34.66l-15.65 12.44c-7.9 6.38-6.86 18.23-6.86 18.23s23.18 87.73 109.79 109.84z" />
                            </svg>
                            @else
                            <i class="mdi {{$socialNetworkData['icon']}} mdi-20px lh-1_0 me-1"></i>
                            @endif
                            {{ucwords($socialNetwork)}}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
</div>
