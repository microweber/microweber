@php
    if ($countActiveProducts > 0) {
    $isInTrashed  = false;
    if(isset($showFilters['trashed']) && $showFilters['trashed']){
        $isInTrashed  = true;
    }

    $findCategory = false;
    if (isset($filters['category'])) {
        $findCategory = get_category_by_id($filters['category']);
    }
@endphp


<div class="card style-1 mb-3">

    <div class="card-header d-flex align-items-center justify-content-between px-md-4">
        <div class="col d-flex justify-content-md-between justify-content-center align-items-center px-0">
            <h5 class="mb-0 d-flex">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-md-flex d-none">
                 <a  class="<?php if($findCategory): ?> text-decoration-none <?php else: ?> text-decoration-none text-dark <?php endif; ?>" onclick="livewire.emit('deselectAllCategories');return false;">{{_e('Products')}}</a>

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
            <a href="{{route('admin.product.create')}}" class="btn btn-outline-success btn-sm js-hide-when-no-items ms-md-4 card-header-add-button">{{_e('Add Product')}}</a>
            </div>
        </div>
    </div>

    <div class="card-body pt-3">
    @include('product::admin.product.livewire.table-includes.table-tr-reoder-js')


    @php
    $showFiltersUnsetCategory = $showFilters;
    if (isset($showFiltersUnsetCategory['category'])) {
        unset($showFiltersUnsetCategory['category']);
    }

    $displayFilters = true;
    if ($products->total() == 0 && empty($showFiltersUnsetCategory)) {
        $displayFilters = false;
    }
    
    $filtersUnsetCategory = $filters;
    if (isset($filtersUnsetCategory['category'])) {
        unset($filtersUnsetCategory['category']);
    }
    if (!empty($filtersUnsetCategory)) {
        $displayFilters = true;
    }
    @endphp


    @if($displayFilters)

    <div class="d-flex">

       <?php if(!$isInTrashed): ?>

       @include('content::admin.content.livewire.components.keyword')

        <div class="ms-0 ms-md-2 mb-3 mb-md-0">

            @include('content::admin.content.livewire.components.button-filter')

            <div class="dropdown-menu p-3" style="width:263px">

                <h6 class="dropdown-header">Taxonomy</h6>
                {{--<label class="dropdown-item"><input type="checkbox" wire:model="showFilters.category"> Category</label>--}}
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.tags"> Tags</label>
                <h6 class="dropdown-header">Shop</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.priceBetween"> Price Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.stockStatus"> Stock Status</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.discount"> Discount</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orders"> Orders</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.qty"> Quantity</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.sku"> Sku</label>

                @php
                $templateFields = mw()->template->get_data_fields('product');
                if (!empty($templateFields)):
                @endphp
                <h6 class="dropdown-header">Template settings</h6>
                @foreach($templateFields as $templateFieldKey=>$templateFieldName)
                    <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.contentData.{{$templateFieldKey}}"> {{$templateFieldName}}</label>
                @endforeach
                @endif

                @php
                $templateFields = mw()->template->get_edit_fields('product');
                if (!empty($templateFields)):
                @endphp
                <h6 class="dropdown-header">Template fields</h6>
                @foreach($templateFields as $templateFieldKey=>$templateFieldName)
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.contentFields.{{$templateFieldKey}}"> {{$templateFieldName}}</label>
                @endforeach
                @endif

                <h6 class="dropdown-header">Other</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> Visible</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> Author</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> Date Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.createdAt"> Created at</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.updatedAt"> Updated at</label>
            </div>
        </div>

        <?php endif; ?>

        @if(!empty($appliedFiltersFriendlyNames))
           @include('content::admin.content.livewire.components.button-clear-filters')
        @endif
    </div>

    @endif

    <div class="d-flex flex-wrap mt-3">
      {{--  @if(isset($showFilters['category']) && $showFilters['category'])
            @include('product::admin.product.livewire.table-filters.category')
        @endif--}}

        @if(isset($showFilters['tags']) && $showFilters['tags'])
            @include('product::admin.product.livewire.table-filters.tags')
        @endif

        @if(isset($showFilters['priceBetween']) && $showFilters['priceBetween'])
            @include('product::admin.product.livewire.table-filters.price-between')
        @endif

        @if(isset($showFilters['stockStatus']) && $showFilters['stockStatus'])
            @include('product::admin.product.livewire.table-filters.stock-status')
        @endif

        @if(isset($showFilters['discount']) && $showFilters['discount'])
            @include('product::admin.product.livewire.table-filters.discount')
        @endif

        @if(isset($showFilters['orders']) && $showFilters['orders'])
            @include('product::admin.product.livewire.table-filters.orders')
        @endif

        @if(isset($showFilters['qty']) && $showFilters['qty'])
            @include('product::admin.product.livewire.table-filters.quantity')
        @endif

        @if(isset($showFilters['sku']) && $showFilters['sku'])
            @include('product::admin.product.livewire.table-filters.sku')
        @endif

        @if(isset($showFilters['contentData']) && $showFilters['contentData'])
            @foreach($showFilters['contentData'] as $contentDataKey=>$contentDataValue)
                @include('product::admin.product.livewire.table-filters.content-data', [
                    'fieldName'=>mw()->template->get_data_field_title($contentDataKey, 'product'),
                    'fieldKey'=>$contentDataKey,
                ])
            @endforeach
        @endif

        @if(isset($showFilters['contentFields']) && $showFilters['contentFields'])
            @foreach($showFilters['contentFields'] as $contentFieldKey=>$contentFieldValue)
                @include('product::admin.product.livewire.table-filters.content-field', [
                    'fieldName'=>mw()->template->get_edit_field_title($contentFieldKey, 'product'),
                    'fieldKey'=>$contentFieldKey,
                ])
            @endforeach
        @endif

        @if(isset($showFilters['visible']) && $showFilters['visible'])
            @include('product::admin.product.livewire.table-filters.visible')
        @endif

        @if(isset($showFilters['userId']) && $showFilters['userId'])
            @include('product::admin.product.livewire.table-filters.author')
        @endif


        @if(isset($showFilters['dateBetween']) && $showFilters['dateBetween'])
            @include('product::admin.product.livewire.table-filters.date-between')
        @endif

        @if(isset($showFilters['createdAt']) && $showFilters['createdAt'])
            @include('product::admin.product.livewire.table-filters.created-at')
        @endif

        @if(isset($showFilters['updatedAt']) && $showFilters['updatedAt'])
            @include('product::admin.product.livewire.table-filters.updated-at')
        @endif
    </div>


        <div class="row  mt-3">
            @if(count($checked) > 0)

                @if (count($checked) == count($products->items()))
                    <div class="col-md-10 mb-2">
                        You have selected all {{ count($checked) }} items.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                    </div>
                @else
                    <div>
                        You have selected {{ count($checked) }} items,
                        Do you want to Select All {{ count($products->items()) }}?
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

            <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative mb-1">

                @if($products->total() > 0)

                @include('content::admin.content.livewire.components.display-as')

                    <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                    @include('content::admin.content.livewire.components.sort')
                    @include('content::admin.content.livewire.components.limit')

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Show columns
                        </button>
                        <div class="dropdown-menu p-3">
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> Id</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.image"> Image</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.title"> Title</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.price"> Price</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.stock"> Stock</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.orders"> Orders</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.quantity"> Quantity</label>
                            <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.author"> Author</label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="page-loading" wire:loading>
                    Loading...
                </div>

            </div>


        </div>

    @if($products->total() > 0)


    <div class="row mt-3">
        <div class="col-md-12">
            @if($displayType == 'card')
                @include('product::admin.product.livewire.display-types.card')
            @endif

            @if($displayType == 'table')
                @include('product::admin.product.livewire.display-types.table')
            @endif
        </div>
    </div>


    {{ $products->links() }}
    @else
        @include('product::admin.product.livewire.no-results-for-filters')
    @endif

</div>
</div>

@php
    } else {
@endphp

@include('product::admin.product.livewire.no-results')

@php
    }
@endphp

