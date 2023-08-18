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
                <livewire:admin-posts-list open-links-in-modal="true" />
                <livewire:admin-content-bulk-options />
            </div>

        </div>
        <div x-show="showEditTab=='settings'">

            <div>
                <label class="live-edit-label">data-page-id</label>
                <livewire:microweber-option::text optionKey="data-page-id" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>
            <div>
                <label class="live-edit-label">data-tags</label>
                <livewire:microweber-option::text optionKey="data-tags" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mt-4 mb-3">
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
                       }
                    }
                }">

                <div x-show="dataDisplayOptions">

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
