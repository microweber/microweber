<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
    <label class="d-block">
        Visible
    </label>

    <select wire:model.stop="filters.orderStatus" class="form-control">
        <option value="">Any</option>
        <option value="new">New</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select>

</div>
