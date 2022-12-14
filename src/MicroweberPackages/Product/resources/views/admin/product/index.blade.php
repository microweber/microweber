<div class="pt-0">

    @include('content::admin.content.index-page-category-tree', [
       'is_shop'=>1,
   ])

    <div class="module-content">
        <livewire:admin-products-list />
        <livewire:admin-content-bulk-options />
    </div>
</div>

@include('content::admin.content.index-scripts')
