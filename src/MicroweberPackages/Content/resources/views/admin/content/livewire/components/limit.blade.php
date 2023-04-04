<div class="d-flex align-items-center mx-1">
    <label class="d-xl-block d-none mx-2">{{ _e('Limit') }}</label>
    <select class="form-select form-select-sm btn btn-outline-primary btn-sm" wire:model="paginate">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="500">500</option>
    </select>
</div>
