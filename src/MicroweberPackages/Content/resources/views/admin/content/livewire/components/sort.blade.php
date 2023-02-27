<div class="d-flex align-items-center ">
    <label class="d-xl-block d-none mx-2">{{ _e('Sort') }}</label>
    <select wire:model.stop="filters.orderBy" class="form-select form-select-sm mw-form-select-filters">
        <option value="">{{ _e('Any') }}</option>
        <option value="id,desc">{{ _e('Id Desc') }}</option>
        <option value="id,asc">{{ _e('Id Asc') }}</option>
    </select>
</div>
