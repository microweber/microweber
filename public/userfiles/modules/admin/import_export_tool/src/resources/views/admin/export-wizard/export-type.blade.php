<div class="d-flex justify-content-center">

    <button type="button" wire:click="selectExportType('pages')" class="import-wizard-select-type">
        <x-import_export_tool::icon name="pages" />
        <div class="mt-2">
            <b>Pages</b>
        </div>
    </button>

    <button type="button" wire:click="selectExportType('posts')" class="import-wizard-select-type">
        <x-import_export_tool::icon name="posts" />
        <div class="mt-2">
            <b>Posts</b>
        </div>
    </button>

    <button type="button" wire:click="selectExportType('products')" class="import-wizard-select-type">
        <x-import_export_tool::icon name="products" />
        <div class="mt-2">
            <b>Products</b>
        </div>
    </button>

    <button type="button" wire:click="selectExportType('categories')" class="import-wizard-select-type">
        <x-import_export_tool::icon name="categories" />
        <div class="mt-2">
            <b>Categories</b>
        </div>
    </button>

</div>
