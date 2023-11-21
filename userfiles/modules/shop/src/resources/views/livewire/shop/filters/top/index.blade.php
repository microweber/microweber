<div class="d-flex justify-content-between">
    <div>
        <label>Search</label>
        <input type="text" class="form-control" wire:model="keywords" placeholder="Type to search...">
    </div>
    <div class="d-flex gap-2">
        <div>
            <label>Sort</label>
            <div>
                <select class="form-control">
                    <option wire:click="filterSort('created_by', 'asc')">Newest</option>
                    <option wire:click="filterSort('created_by', 'desc')">Oldest</option>
                    <option wire:click="filterSort('title', 'asc')">Title: A-Z</option>
                    <option wire:click="filterSort('title', 'desc')">Title: Z-A</option>
                    <option wire:click="filterSort('price', 'asc')">Price: Low to High</option>
                    <option wire:click="filterSort('price', 'desc')">Price: High to Low</option>
                </select>
            </div>
        </div>
        <div>
            <label>Limit</label>
            <div>
                <select class="form-control">
                    <option wire:click="filterLimit(1)">1</option>
                    <option wire:click="filterLimit(12)">12</option>
                    <option wire:click="filterLimit(24)">24</option>
                    <option wire:click="filterLimit(48)">48</option>
                    <option wire:click="filterLimit(96)">96</option>
                </select>
            </div>
        </div>
    </div>
</div>
