<div>

    <script>
        $(document).ready(function () {
            mw.top().dialog.get('.mw_modal_live_edit_settings').resize(900);
        });
    </script>

    <div x-data="{
    showEditTab: 'content'
    }">

        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <a href="#" x-on:click="showEditTab = 'content'" :class="{ 'active': showEditTab == 'content' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    Posts
                </a>
                <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    Settings
                </a>
                <a href="#" x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    Design
                </a>
            </div>
        </div>

        <div x-show="showEditTab=='content'">

            <div>
                <livewire:admin-posts-list :filters="$postListFilters" open-links-in-modal="true" />
                <livewire:admin-content-bulk-options />
            </div>

        </div>
        <div x-show="showEditTab=='settings'">

            <div>
                <label class="live-edit-label">From Source</label>
                <livewire:microweber-option::select-page optionKey="data-page-id" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div>
                <label class="live-edit-label">Filter Tags</label>
                <livewire:microweber-option::select-tags optionKey="data-tags" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mb-3">
                <label class="live-edit-label">Display on post</label>
                @php
                    $radioOptions = [
                        '' => 'Show default information from module skin',
                        'thumbnail,title' => 'Show custom information',
                    ];
                @endphp
                <livewire:microweber-option::dropdown :dropdownOptions="$radioOptions" optionKey="data-display" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div
                x-data="{'dataDisplayOptions': @if (!empty(get_option('data-display', $moduleId))) true @else false @endif }"

                @mw-option-saved.window="function() {
                    if ($event.detail.optionKey == 'data-display') {
                        if ($event.detail.optionValue.length > 0) {
                           dataDisplayOptions = true;
                       } else {
                           dataDisplayOptions = false;
                      }
                    }
                }">

                <div x-show="dataDisplayOptions">

                    <div>
                        <div>
                            <label class="live-edit-label">{{__('Title Limit')}} </label>
                            <livewire:microweber-option::text optionKey="data-title-limit" :optionGroup="$moduleId" :module="$moduleType"  />
                        </div>
                        <div>
                            <label class="live-edit-label">{{__('Description Limit')}} </label>
                            <livewire:microweber-option::text optionKey="data-character-limit" :optionGroup="$moduleId" :module="$moduleType"  />
                        </div>
                        <div>
                            <label class="live-edit-label">{{__('Read more text')}} </label>
                            <livewire:microweber-option::text optionKey="data-read-more-text" :optionGroup="$moduleId" :module="$moduleType"  />
                        </div>
                        <div>
                            <label class="live-edit-label">{{__('Post per page')}} </label>
                            <livewire:microweber-option::text optionKey="data-limit" :optionGroup="$moduleId" :module="$moduleType"  />
                        </div>

                        <div class="mb-3">
                            <label class="live-edit-label">Order by</label>
                            @php
                                $radioOptions = [
                                    'position+asc' => 'Position (ASC)',
                                    'position+desc' => 'Position (DESC)',
                                    'created_at+asc' => 'Date (ASC)',
                                    'created_at+desc' => 'Date (DESC)',
                                ];
                            @endphp
                            <livewire:microweber-option::dropdown :dropdownOptions="$radioOptions" optionKey="data-order-by" :optionGroup="$moduleId" :module="$moduleType"  />
                        </div>

                    </div>

                    <div class="mt-4 mb-3">
                        @php
                            $checkboxOptions = [
                                'thumbnail' => 'Thumbnail',
                                'title' => 'Title',
                                'description' => 'Description',
                                'read_more' => 'Read More',
                                'created_at' => 'Date',
                            ];
                        @endphp
                        <livewire:microweber-option::checkbox :checkboxOptions="$checkboxOptions" optionKey="data-show" :optionGroup="$moduleId" :module="$moduleType"  />
                    </div>


                </div>

            </div>

        </div>

        <div x-show="showEditTab=='design'">
            <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType" />
        </div>

    </div>

</div>
