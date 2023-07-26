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
        <div class="d-flex justify-content-between align-items-center collapseNav-initialized">
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
                                <i class="mdi {{$socialNetworkData['icon']}} mdi-20px lh-1_0 me-1"></i> {{ucwords($socialNetwork)}}
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
