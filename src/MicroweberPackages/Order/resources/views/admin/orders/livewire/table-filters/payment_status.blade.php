<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
    <label class="d-block">
        Payment Status
    </label>

    <select wire:model.stop="filters.isPaid" class="form-control">
        <option value="">Any</option>
        <option value="1">Paid</option>
        <option value="0">Unpaid</option>
    </select>

</div>
