<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Registered User
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItem = false;
            if (isset($filters['userId'])) {
                $selectedItem = $filters['userId'];
            }
        @endphp
        @livewire('admin-users-autocomplete', ['selectedItem'=>$selectedItem])
    </div>
</div>
