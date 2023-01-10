<?php
$isInTrashed  = false;
if(isset($showFilters['trashed']) && $showFilters['trashed']){
    $isInTrashed  = true;
}

$findCategory = false;
if (isset($filters['category'])) {
    $findCategory = get_category_by_id($filters['category']);
}
?>


<div class="card style-1 mb-3">

    <div class="card-header d-flex align-items-center justify-content-between px-md-4">
        <div class="col d-flex justify-content-md-between justify-content-center align-items-center px-0">
            <h5 class="mb-0 d-flex">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-md-flex d-none">
                    <a  class="<?php if($findCategory): ?> text-decoration-none <?php else: ?> text-decoration-none text-dark <?php endif; ?>" onclick="livewire.emit('deselectAllCategories');return false;">{{_e('Website')}}</a>

                    @if($findCategory)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                        {{$findCategory['title']}}
                    @endif

                    @if($isInTrashed)
                        <span class="text-muted">&nbsp; &raquo; &nbsp;</span>  <i class="mdi mdi-trash-can"></i><?php _e('Trash') ?>
                    @endif
                </strong>

                @if($findCategory)
                    <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">
                        <i class="fa fa-times-circle"></i>
                    </a>
                @endif
            </h5>
            <div>
                @if($findCategory)
                    <a href="{{category_link($findCategory['id'])}}" target="_blank" class="btn btn-link btn-sm js-hide-when-no-items ms-md-4">{{_e('View category')}}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body pt-3">

        @include('content::admin.content.livewire.table-includes.table-tr-reoder-js')

        @php
            $showFiltersUnsetCategory = $showFilters;
            if (isset($showFiltersUnsetCategory['category'])) {
                unset($showFiltersUnsetCategory['category']);
            }

            $displayFilters = true;
            if ($contents->total() == 0 && empty($showFiltersUnsetCategory)) {
                $displayFilters = false;
            }
        @endphp

        @if($displayFilters)
        <div class="d-flex">

            <?php if(!$isInTrashed): ?>
            <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                <input wire:model.stop="filters.keyword" type="search" placeholder="Search by keyword..." class="form-control" style="width: 300px; height: 50px;">
            </div>

            <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>

                    Filters
                </button>
                <div class="dropdown-menu p-3" style="width:263px">
                    <h6 class="dropdown-header">Taxonomy</h6>
                    {{--<label class="dropdown-item"><input type="checkbox" wire:model="showFilters.category"> Category</label>--}}
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.tags"> Tags</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> Visible</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> Author</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> Date Range</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.createdAt"> Created at</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.updatedAt"> Updated at</label>
                </div>
            </div>

            <?php endif; ?>

            @if(!empty($appliedFiltersFriendlyNames))
                <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                    <div class="btn-group">
                        <button class="btn btn-outline-danger" wire:click="clearFilters">Clear</button>
                    </div>
                </div>
            @endif
        </div>
        @endif

        <div class="d-flex flex-wrap mt-3">

            @if(isset($showFilters['tags']) && $showFilters['tags'])
                @include('content::admin.content.livewire.table-filters.tags')
            @endif

            @if(isset($showFilters['visible']) && $showFilters['visible'])
                @include('content::admin.content.livewire.table-filters.visible')
            @endif

            @if(isset($showFilters['userId']) && $showFilters['userId'])
                @include('content::admin.content.livewire.table-filters.author')
            @endif


            @if(isset($showFilters['dateBetween']) && $showFilters['dateBetween'])
                @include('content::admin.content.livewire.table-filters.date-between')
            @endif

            @if(isset($showFilters['createdAt']) && $showFilters['createdAt'])
                @include('content::admin.content.livewire.table-filters.created-at')
            @endif

            @if(isset($showFilters['updatedAt']) && $showFilters['updatedAt'])
                @include('content::admin.content.livewire.table-filters.updated-at')
            @endif
        </div>
        <div class="row  mt-3">
            @if(count($checked) > 0)

                @if (count($checked) == count($contents->items()))
                    <div class="col-md-10 mb-2">
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($contents->items()) }}?
                        <button type="button" class="btn btn-link btn-sm" wire:click="selectAll">Select All</button>
                    </div>
                @endif
            @endif

            @if(count($checked) > 0)
                <div class="pull-left">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Bulk Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" wire:click="multipleMoveToCategory">Move To Category</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multiplePublish">Publish</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUnpublish">Unpublish</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDelete">Move to trash</button></li>
                            <li><button class="dropdown-item" type="button" wire:click="multipleDeleteForever">Delete Forever</button></li>
                            <?php if($isInTrashed): ?>
                            <li><button class="dropdown-item" type="button" wire:click="multipleUndelete">Restore from trash</button></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="row mt-3">

            <div style="height: 60px" class="bulk-actions-show-columns mw-js-loading position-relative">

                @if($contents->total() > 0)
                <div class="d-inline-block mx-1">
                    <span class="d-md-block d-none mb-1"> Display as </span>
                    <div class="btn-group mb-4">
                        <a href="#" wire:click="setDisplayType('card')" class="btn mw-content-vision-tabs @if($displayType=='card') active @endif">
                            <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        <?php _e('Card') ?> </a>
                        <a href="#" wire:click="setDisplayType('table')" class="btn mw-content-vision-tabs @if($displayType=='table') active @endif">
                            <svg class="me-1" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M20.016 8.016v-4.031h-4.031v4.031h4.031zM20.016 14.016v-4.031h-4.031v4.031h4.031zM20.016 20.016v-4.031h-4.031v4.031h4.031zM14.016 8.016v-4.031h-4.031v4.031h4.031zM14.016 14.016v-4.031h-4.031v4.031h4.031zM14.016 20.016v-4.031h-4.031v4.031h4.031zM8.016 8.016v-4.031h-4.031v4.031h4.031zM8.016 14.016v-4.031h-4.031v4.031h4.031zM8.016 20.016v-4.031h-4.031v4.031h4.031zM20.016 2.016q0.797 0 1.383 0.586t0.586 1.383v16.031q0 0.797-0.586 1.383t-1.383 0.586h-16.031q-0.797 0-1.383-0.586t-0.586-1.383v-16.031q0-0.797 0.586-1.383t1.383-0.586h16.031z"></path>
                            </svg> <?php _e('Table') ?> </a>
                    </div>
                </div>

                <div class="pull-right">

                    <div class="d-inline-block mx-1">

                        <span class="d-md-block d-none mb-2">Sort</span>
                        <select wire:model.stop="filters.orderBy" class="form-control form-control-sm">
                            <option value="">Any</option>
                            <option value="id,desc">Id Desc</option>
                            <option value="id,asc">Id Asc</option>
                        </select>
                    </div>

                    <div class="d-inline-block mx-1">

                        <span class="d-md-block d-none mb-2">Limit</span>
                        <select class="form-control form-control-sm" wire:model="paginate">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Show columns
                        </button>
                        <div class="dropdown-menu p-3">
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> Id</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> Image</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.title"> Title</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.author"> Author</label>
                        </div>
                    </div>
                </div>
                @endif



                    <script>
                        mw.spinner({
                            size: 30,
                            element: ".mw-js-loading",
                            decorate: true,

                        });

                        mw.spinner({
                            size: 30,
                            element: ".mw-js-loading",
                            decorate: true,

                        }).remove();
                    </script>
            </div>


        </div>
        @if($contents->total() > 0)

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

            {{ $contents->links() }}

        @else
            @include('content::admin.content.livewire.no-results')
        @endif

    </div>
</div>


