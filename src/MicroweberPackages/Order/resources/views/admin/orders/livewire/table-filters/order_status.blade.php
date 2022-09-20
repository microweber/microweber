<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-order-status-filter">
    <label class="d-block">
        Order Status
    </label>

    <select wire:model.stop="filters.orderStatus" class="form-control">
        <option value="">Any</option>
        <option value="new">New</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select>

</div>
