<div class="row mb-4">
    <div class="col-xl-6 col-lg-5 py-lg-0 py-4">
        <p>
            {{ _e("Displaying") }} {{$count}} {{ _e("of") }} {{$total}} {{ _e("result(s)") }}.
        </p>
    </div>
    <div class="col-xl-6 col-lg-7 d-block d-sm-flex justify-content-end ms-auto">
        <div class="col-md-6 col-12 col-sm px-1 ms-auto">
            <select wire:model.live="limit" class="form-select">
                <option value="10">10 {{ _e('per page') }}</option>
                <option value="25">25 {{ _e('per page') }}</option>
                <option value="50">50 {{ _e('per page') }}</option>
            </select>
        </div>
        <div class="col-md-6 col-12 col-sm px-1 ms-auto">
            <select wire:model.live="sortBy" class="form-select">
                <option value="created_at">{{ _e('Sort by date') }}</option>
                <option value="title">{{ _e('Sort by title') }}</option>
            </select>
        </div>
        <div class="col-md-6 col-12 col-sm px-1 ms-auto">
            <select wire:model.live="sortOrder" class="form-select">
                <option value="desc">{{ _e('Descending') }}</option>
                <option value="asc">{{ _e('Ascending') }}</option>
            </select>
        </div>
    </div>
</div>
