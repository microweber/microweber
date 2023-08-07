<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);
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

        <div>
            <label class="live-edit-label">
                {{_e('Search string')}}
                <a href="https://dev.twitter.com/rest/public/search" target="_blank">[?]</a>
            </label>
            <livewire:microweber-option::text  placeholder="Example: technology" optionKey="search_string" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <label class="live-edit-label">
                {{_e('Number of items')}}
            </label>
            <small class="text-muted d-block mb-2">{{ _e('Type your prefer number of displayed posts') }}</small>
            <livewire:microweber-option::numeric optionKey="number_of_items" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <button class="btn btn-link btn-sm px-0 pt-3" onclick="$('#toggle-advanced-settings-twitter').toggle();"><?php _e("Advanced settings") ?></button>
        <div class="pt-3" style="display: none;" id="toggle-advanced-settings-twitter">
            <h5 class="font-weight-bold mb-3">
                {{_e('Access Token Settings')}}
            </h5>


            <div class="mt-3">
                <label class="live-edit-label">
                    {{_e('Consumer Key')}}
                </label>
                <livewire:microweber-option::text optionKey="consumer_key" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mt-3">
                <label class="live-edit-label">
                    {{_e('Consumer Secret')}}
                </label>
                <livewire:microweber-option::text optionKey="consumer_secret" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mt-3">
                <label class="live-edit-label">
                    {{_e('Access Token')}}
                </label>
                <livewire:microweber-option::text optionKey="access_token" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mt-3">
                <label class="live-edit-label">
                    {{_e('Access Token Secret')}}
                </label>
                <livewire:microweber-option::text optionKey="access_token_secret" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <p>{{_e('Get your Twitter access keys')}}
                <a href="https://apps.twitter.com/app" target="_blank" class="mw-ui-link">
                    {{_e('from here')}}
                </a>
            </p>
        </div>


    </div>

    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>
    @endif

</div>
