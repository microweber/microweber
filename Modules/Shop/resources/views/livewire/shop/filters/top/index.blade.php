{{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB finding #9:
     wire:model.live.debounce.500ms="keywords" fired a Livewire round-trip on every
     keystroke — server-load amplifier on shops with thousands of
     products. .debounce.500ms waits 500ms after the last keystroke
     before firing, dropping the per-character query rate. --}}
<div class="d-flex justify-content-between">
    <div>
        <label for="js-shop-search-keywords">Search</label>
        <input id="js-shop-search-keywords" type="text" class="form-control" wire:model.live.debounce.500ms="keywords" placeholder="Type to search...">
    </div>
    <div class="d-flex gap-2">
        {{-- audit-test 2026-05-07 Shop audit finding 1 (BLOCKER):
             wire:click on <option> never fires (browsers fire change on
             <select>, not click on <option>). Sort/Limit dropdowns were
             doing nothing. Fixed: bind wire:model.live on the <select>
             with proper value attributes; ShopComponent's
             updatedSortKey/updatedLimit handle the state swap. --}}
        <div>
            <label>Sort</label>
            <div>
                <select class="form-control" wire:model.live.debounce.500ms="sortKey">
                    <option value="created_by_asc">Newest</option>
                    <option value="created_by_desc">Oldest</option>
                    <option value="title_asc">Title: A-Z</option>
                    <option value="title_desc">Title: Z-A</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                </select>
            </div>
        </div>
        <div>
            <label>Limit</label>
            <div>
                <select class="form-control" wire:model.live.debounce.500ms="limit">
                    {{-- task-2026-06-08-shoplimit — dropped the nonsensical "1 per page" option --}}
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                    <option value="96">96</option>
                </select>
            </div>
        </div>
    </div>
</div>
