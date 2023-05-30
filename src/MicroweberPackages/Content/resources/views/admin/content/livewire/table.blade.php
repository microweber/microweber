<div class="card-body mb-3">

    @include('content::admin.content.livewire.set-tree-active-content')

    <div class=" ">

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @if($displayFilters)
       <div class="row py-3">
           <div class="d-flex align-items-center justify-content-between">
               @include('content::admin.content.livewire.card-header')

               <div class="ms-4 input-icon col-xl-5 col-sm-5 col-12  ">
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
                                                 <label class=" form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" wire:model="showFilters.{{ $dropdownFilter['key'] }}" checked="">
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


               <div class="text-end">
                   <a href="" class="btn btn-primary"><?php _e("Create new page") ?></a>
               </div>


               @if(!empty($appliedFiltersFriendlyNames))
                   @include('content::admin.content.livewire.components.button-clear-filters')
               @endif
           </div>
       </div>
        @endif

       <div class="row py-3">
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

                    <div class="col-md-7 col-12 d-flex justify-content-end align-items-center mw-filters-sorts-mobile pe-1">
                        <div class="">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" data-bs-toggle="dropdown" aria-expanded="false">
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

                        <div class="ms-2" x-data="{ openSortDropdown: false }">
                            <span @click="openSortDropdown =! openSortDropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480.037 904.131q-20.994 0-35.831-14.915-14.836-14.914-14.836-35.857 0-20.72 14.896-35.724 14.897-15.005 35.816-15.005 21.114 0 35.831 15.117 14.717 15.116 14.717 35.913 0 20.797-14.799 35.634-14.8 14.837-35.794 14.837Zm0-277.501q-20.994 0-35.831-14.896-14.836-14.897-14.836-35.816 0-21.114 14.896-35.831 14.897-14.717 35.816-14.717 21.114 0 35.831 14.799 14.717 14.8 14.717 35.794 0 20.994-14.799 35.831-14.8 14.836-35.794 14.836Zm0-277.26q-20.994 0-35.831-14.988-14.836-14.987-14.836-36.032 0-21.046 14.896-35.883 14.897-14.837 35.816-14.837 21.114 0 35.831 14.979 14.717 14.978 14.717 36.024t-14.799 35.891q-14.8 14.846-35.794 14.846Z"/></svg>
                            </span>

                            <span class="table-blade-sortable-elements bg-light shadow-sm align-items-center justify-content-center p-3" style=" display: none;" x-show="openSortDropdown">
                                @include('content::admin.content.livewire.components.sort')
                                @include('content::admin.content.livewire.components.limit')
                            </span>
                        </div>
                    </div>

                </div>

                @if(count($checked) > 0)
                    <div class="row  mt-3">

                        @if (count($checked) == count($contents->items()))
                            <div class="col-md-10 mb-2">
                                You have selected all {{ count($checked) }} items.
                                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">{{ _e('Deselect All') }}</button>
                            </div>
                        @else
                            <div>
                                You have selected {{ count($checked) }} items,
                                Do you want to Select All {{ count($contents->items()) }}?
                                <button type="button" class="btn btn btn-sm btn-outline-primary btn-link-to-bordered" wire:click="selectAll">{{ _e('Select All') }}</button>
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
