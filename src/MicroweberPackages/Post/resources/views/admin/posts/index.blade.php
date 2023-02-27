<div class="pt-0">

    @include('content::admin.content.index-page-category-tree', [
         'is_blog'=>1,
     ])

    <div class="module-content">

        <livewire:admin-posts-list />
        <livewire:admin-content-bulk-options />

    </div>
</div>

@include('content::admin.content.index-scripts')
