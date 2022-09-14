<div class=" col-12 col-sm-6 col-md-3 col-lg-4 mb-4 ">
    <label class="d-block">
        Quantity
    </label>

    <div class="mb-3 mb-md-0 input-group">
        <select class="form-control" wire:model.stop="filters.qtyOperator">
            <option value="">Equal</option>
            <option value="greater">More than</option>
            <option value="lower">Lower than</option>
        </select>
        <input type="number" class="form-control" placeholder="Quantity" wire:model.stop="filters.qty">
    </div>

</div>
