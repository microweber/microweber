<div class="pb-6 border-b border-white">
    <p class="mb-2">Non Datatable Component Event Demo</p>
    <button class="p-2 border border-white rounded" wire:click="$emit('setSort', 'name', 'asc')">Sort Name Asc</button>
    <button class="p-2 border border-white rounded" wire:click="$emit('setSort', 'name', 'desc')">Sort Name Desc</button>
    <button class="p-2 border border-white rounded" wire:click="$emit('clearSorts')">Clear Sorts</button>
    <button class="p-2 border border-white rounded" wire:click="$emit('setFilter', 'active', '1')">Filter Active Yes</button>
    <button class="p-2 border border-white rounded" wire:click="$emit('setFilter', 'active', '0')">Filter Active No</button>
    <button class="p-2 border border-white rounded" wire:click="$emit('clearFilters')">Clear Filters</button>
</div>
