<div class="card-body mb-3">


    @include('content::admin.content.livewire.set-tree-active-content')

    <div>

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @if($displayFilters)
            <div class="row py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap">

                    @include('content::admin.content.livewire.card-header')

                    <div class="col-lg-5 col-sm-6 col ms-md-4 me-lg-0 me-4 input-icon">
                        <div class="input-group input-group-flat ">
                            <input type="text" wire:model.debounce.500ms="filters.keyword" placeholder="<?php _e("Search by keyword"); ?>..." class="form-control" autocomplete="off">
                            <span class="input-group-text">
                                @include('content::admin.content.livewire.components.button-filter')
                                <div class="dropdown-menu p-2">
                                    @if(!empty($dropdownFilters))
                                        @foreach($dropdownFilters as $dropdownFilterGroup)
                                            <div class="">
                                                 <h6 class="dropdown-header">{{ $dropdownFilterGroup['groupName']  }}</h6>
                                                @foreach($dropdownFilterGroup['filters'] as $dropdownFilter)
                                                    <div class="dropdown-item">
                                                         <label class=" form-check form-check-inline mb-0">
                                                            <input class="form-check-input me-2" type="checkbox" wire:model="showFilters.{{ $dropdownFilter['key'] }}" checked="">
                                                             <span class="form-check-label">{{ $dropdownFilter['name'] }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                             </div>
                                        @endforeach
                                    @endif
                                </div>
                            </span>
                        </div>
                    </div>


                    <div class="col-sm-3 col-auto mt-sm-0 text-sm-end text-center d-flex justify-content-sm-end justify-content-center">
                        @if($this->contentType == 'page')
                        <a href="{{route('admin.page.create')}}" class="btn btn-dark">
                            <svg fill="currentColor" class="me-sm-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                            <span class="d-sm-block d-none">{{_e("New Page")}}</span>
                        </a>
                        @endif
                        @if($this->contentType == 'post')
                            <a href="{{route('admin.post.create')}}" class="btn btn-dark">
                                <svg fill="currentColor" class="me-sm-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                                <span class="d-sm-block d-none">{{_e("New Post")}}</span>
                            </a>
                        @endif
                        @if($this->contentType == 'product')
                            <a href="{{route('admin.product.create')}}" class="btn btn-dark">
                               <svg fill="currentColor" class="me-sm-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                                <span class="d-sm-block d-none">{{_e("New Product")}}</span>
                            </a>
                        @endif
                    </div>


                    @if(!empty($appliedFiltersFriendlyNames))
                        @include('content::admin.content.livewire.components.button-clear-filters')
                    @endif
                </div>
            </div>
        @endif

        <div class="row py-3 dropdown-filters-if-naked">
            <div class="d-flex flex-wrap">

                @php
                    if(!empty($dropdownFilters)) {
                        foreach($dropdownFilters as $dropdownFilterGroup) {
                            foreach($dropdownFilterGroup['filters'] as $dropdownFilter) {
                                $skipDropdownFilter = false;
                                if(isset($dropdownFilter['type']) && $dropdownFilter['type'] == 'separator') {
                                    $skipDropdownFilter = true;
                                }
                                if (!$skipDropdownFilter) {

                                    if (isset($dropdownFilter['key']) && strpos($dropdownFilter['key'], '.') !== false) {
                                            $dropdownFilterExp = explode('.', $dropdownFilter['key']);
                                            if (isset($showFilters[$dropdownFilterExp[0]][$dropdownFilterExp[1]])) {
                @endphp
                @include('content::admin.content.livewire.table-filters.' . $dropdownFilterExp[0], [
                   'fieldName'=>$dropdownFilter['name'],
                   'fieldKey'=>$dropdownFilterExp[1],
                   'fieldValue'=>$showFilters[$dropdownFilterExp[0]][$dropdownFilterExp[1]],
                  ])
                @php
                    }
                continue;
            }


                if (isset($showFilters[$dropdownFilter['key']]) && $showFilters[$dropdownFilter['key']]) {
                @endphp
                @if (isset($dropdownFilter['viewNamespace']))
                    @include($dropdownFilter['viewNamespace'])
                @else
                    @include('content::admin.content.livewire.table-filters.'.$dropdownFilter['key'])
                @endif
                @php
                    }
                }
            }
    }
}
                @endphp

            </div>
        </div>


        @if($contents->total() > 0)
            <div class="row py-3">
                <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">
                    <div class="col-md-5 col-12 d-flex justify-content-start align-items-center px-0 ">
                        @include('content::admin.content.livewire.components.display-as')
                    </div>

                    <div class="col-md-7 col-12 d-flex justify-content-end align-items-center mw-filters-sorts-mobile">

                        @if($displayType=='table')
                            <div>
                                <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ _e('Columns') }}
                                </button>
                                <div class="dropdown-menu p-3">
                                    @foreach($showColumns as $column=>$columnShow)
                                        <div class="dropdown-item">
                                            <label wire:key="show-column-{{ $loop->index }}"  class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" wire:model="showColumns.{{$column}}">
                                                <span class="form-check-label">
                                                    {{ _e(ucfirst($column)) }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="ms-2" x-data="{ openSortDropdown: false }">
                            <span @click="openSortDropdown =! openSortDropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M479.788-192Q450-192 429-213.212q-21-21.213-21-51Q408-294 429.212-315q21.213-21 51-21Q510-336 531-314.788q21 21.213 21 51Q552-234 530.788-213q-21.213 21-51 21Zm0-216Q450-408 429-429.212q-21-21.213-21-51Q408-510 429.212-531q21.213-21 51-21Q510-552 531-530.788q21 21.213 21 51Q552-450 530.788-429q-21.213 21-51 21Zm0-216Q450-624 429-645.212q-21-21.213-21-51Q408-726 429.212-747q21.213-21 51-21Q510-768 531-746.788q21 21.213 21 51Q552-666 530.788-645q-21.213 21-51 21Z"/></svg>
                            </span>

                            <span class="table-blade-sortable-elements bg-light shadow-sm align-items-center justify-content-center p-3" style=" display: none;" x-show="openSortDropdown">
                                @include('content::admin.content.livewire.components.sort')
                                @include('content::admin.content.livewire.components.limit')
                            </span>
                        </div>
                    </div>

                </div>

                @if(count($checked) > 0)
                    <div class="mt-3">

                        @if (count($checked) == count($contents->items()))
                            <div class="col-md-10 mb-2">
                                You have selected all {{ count($checked) }} items.
                                <button type="button" class="btn btn-link" wire:click="deselectAll">{{ _e('Deselect All') }}</button>
                            </div>
                        @else
                            <div>
                                You have selected {{ count($checked) }} items,
                                do you want to select all {{ count($contents->items()) }}?
                                <button type="button" class="btn btn-link" wire:click="selectAll">{{ _e('Select All') }}</button>
                            </div>
                        @endif

                        @if(count($checked) > 0)
                            <div class="pull-left">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
                @endif
            </div>
            <div class="row py-3">
                <div class="col-md-12">

                    @if($displayType == 'card')
                        @if(isset($this->displayTypesViews['card']))
                            @include($this->displayTypesViews['card'])
                        @else
                            @include('content::admin.content.livewire.display-types.card')
                        @endif
                    @endif

                    @if($displayType == 'table')
                        @if(isset($this->displayTypesViews['table']))
                            @include($this->displayTypesViews['table'])
                        @else
                            @include('content::admin.content.livewire.display-types.table')
                        @endif
                    @endif

                </div>
            </div>

            <div class="row py-3">
                <div class="d-flex justify-content-center">

                    <div style="width: 100%">
                        <span class="text-muted">{{ $contents->total() }} results found</span>
                    </div>

                    <div>
                        {{ $contents->links() }}
                    </div>
                </div>
            </div>

        @else
            @include('content::admin.content.livewire.no-results-for-filters')
        @endif

    </div>
</div>
