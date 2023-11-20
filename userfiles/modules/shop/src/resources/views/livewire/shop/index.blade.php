<div>

    <h1>Shop</h1>


    <div class="row">
        <div class="col-md-3" style="background:#f1f1f1;border-radius:5px;padding-top:15px;">
            Filters:

            @if(!empty($availableCategories))
                @include('microweber-module-shop::livewire.shop.filters.categories.index')
            @endif

            @if(!empty($availableTags))
                @include('microweber-module-shop::livewire.shop.filters.tags.index')
            @endif

            @if(!empty($availableCustomFields))
                @include('microweber-module-shop::livewire.shop.filters.custom_fields.index')
            @endif

        </div>
        <div class="col-md-9">

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

            <div class="row mt-4">
                @foreach($products as $product)
                    <div class="col-xl-6 col-lg-6 col-sm-12 mb-5">
                        @include('microweber-module-shop::livewire.shop.product-card')
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mb-3">
                {{ $products->links("livewire-tables::specific.bootstrap-4.pagination") }}
            </div>
        </div>
    </div>

</div>
