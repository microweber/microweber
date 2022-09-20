<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4">
    <label class="d-block">
        Category
    </label>
    <input wire:model.stop="filters.category" id="js-filter-category" type="hidden" />
    <input wire:model.stop="filters.page" id="js-filter-page" type="hidden" />

    <button class="btn btn-outline-primary" onclick="categoryFilterSelectTree()">
        <i class="fa fa-list"></i> Select Categories
    </button>

</div>
