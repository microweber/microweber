<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Shipping Status
    </label>

    <select wire:model.stop="filters.shipping.status" class="form-control">
        <option value="">Any</option>
        <option value="received">Received</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
    </select>

</div>
