<div class="card style-1 mb-3">

    @include('content::admin.content.livewire.card-header')

    <div class="card-body pt-3">

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @if($displayFilters)
        <div class="d-flex flex-wrap">

            <?php if(!$isInTrashed): ?>

            @include('content::admin.content.livewire.components.keyword')

            <div class="col-xl-2 col-sm-3 col-12 mb-3 mb-md-0 ps-0">
                @include('content::admin.content.livewire.components.button-filter')
                <div class="dropdown-menu p-3" style="width:263px">


                    @if(!empty($dropdownFilters))

                        @foreach($dropdownFilters as $dropdownFilter)
                           @if(isset($dropdownFilter['type']) && $dropdownFilter['type'] == 'separator')
                                <h6 class="dropdown-header">{{ $dropdownFilter['name']  }}</h6>
                           @else
                                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.{{ $dropdownFilter['key'] }}"> {{ $dropdownFilter['name'] }}</label>
                           @endif
                        @endforeach

                    @endif

                </div>
            </div>

            <?php endif; ?>

            @if(!empty($appliedFiltersFriendlyNames))
                @include('content::admin.content.livewire.components.button-clear-filters')
            @endif
        </div>
        @endif

        <div class="d-flex flex-wrap mt-3">

            @php
            if(!empty($dropdownFilters)) {
                foreach($dropdownFilters as $dropdownFilter) {
                    $skipDropdownFilter = false;
                    if(isset($dropdownFilter['type']) && $dropdownFilter['type'] == 'separator') {
                        $skipDropdownFilter = true;
                    }
                    if (!$skipDropdownFilter) {
                        $dropdownFilterKey = $dropdownFilter['key'];
                        if (strpos($dropdownFilter['key'], '.') !== false) {
                            $dropdownFilterKeyExp = explode('.', $dropdownFilter['key']);
                            if (isset($dropdownFilterKeyExp[0])) {
                                $dropdownFilterKey = $dropdownFilterKeyExp[0];
                            }
                        }
                        $tableFilterParams = [];
                        if (isset($dropdownFilter['name'])) {
                            $tableFilterParams['fieldName'] = $dropdownFilter['name'];
                        }
                        if (isset($dropdownFilter['key'])) {
                            $tableFilterParams['fieldKey'] = $dropdownFilter['key'];
                        }
                        if (isset($showFilters[$dropdownFilterKey]) && $showFilters[$dropdownFilterKey]) {
                         @endphp

                            @if (isset($dropdownFilter['viewNamespace']))
                                @include($dropdownFilter['viewNamespace'], $tableFilterParams)
                            @else
                                @include('content::admin.content.livewire.table-filters.'.$dropdownFilterKey, $tableFilterParams)
                            @endif

                        @php
                        }
                    }
                }
            }
            @endphp

        </div>
        <div class="row  mt-3">
            @if(count($checked) > 0)

                @if (count($checked) == count($contents->items()))
                    <div class="col-md-10 mb-2">
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">{{ _e('Deselect All') }}</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($contents->items()) }}?
                        <button type="button" class="btn btn-link btn-sm" wire:click="selectAll">{{ _e('Select All') }}</button>
                    </div>
                @endif
            @endif

            @if(count($checked) > 0)
                <div class="pull-left">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ _e('Bulk Actions') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" wire:click="multipleMoveToCategory">{{ _e('Move To Category') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multiplePublish">{{ _e('Publish') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUnpublish">{{ _e('Unpublish') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDelete">{{ _e('Move to trash') }}</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDeleteForever">{{ _e('Delete Forever') }}</button></li>
                            <?php if($isInTrashed): ?>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUndelete">{{ _e('Restore from trash') }}</button></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        @if($contents->total() > 0)
            <div class="row mt-3">
                <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">

                    @include('content::admin.content.livewire.components.display-as')

                    <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                        @include('content::admin.content.livewire.components.sort')
                        @include('content::admin.content.livewire.components.limit')

                        <div class="">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" style="padding: 10px;" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ _e('Columns') }}
                            </button>
                            <div class="dropdown-menu p-3">
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> {{ _e('Id') }}</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> {{ _e('Image') }}</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.title"> {{ _e('Title') }}</label>
                                <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.author"> {{ _e('Author') }}</label>
                            </div>
                        </div>
                    </div>

                    <script>
                            mw.spinner({
                                size: 30,
                                element: ".mw-js-loading",
                                decorate: true,

                            }).remove();
                        </script>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    @if($displayType == 'card')
                        @include('content::admin.content.livewire.display-types.card')
                    @endif

                    @if($displayType == 'table')
                        @include('content::admin.content.livewire.display-types.table')
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-center">

                <div style="width: 100%">
                    <span class="text-muted">{{ $contents->total() }} results found</span>
                </div>

                <div>
                    {{ $contents->links() }}
                </div>
            </div>

        @else
            @include('content::admin.content.livewire.no-results-for-filters')
        @endif

    </div>
</div>
