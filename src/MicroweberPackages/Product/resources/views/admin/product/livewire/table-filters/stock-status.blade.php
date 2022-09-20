<div class=" col-12 col-sm-6 col-md-3 col-lg-4 mb-4 ">
    <label class="d-block">
        Stock Status
    </label>

    <select wire:model.stop="filters.inStock" class="form-control">
        <option value="">Any</option>
        <option value="1">In Stock</option>
        <option value="0">Out Of Stock</option>
    </select>

</div>
