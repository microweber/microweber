
<div class="my-3 mw-shop-filter-offers">
    <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Discount (%)') }}</h3>
    @php $offersSelectId = 'mw-shop-filter-offers-' . md5(($moduleId ?? '') . ($moduleType ?? 'offers')); @endphp
    <label for="{{ $offersSelectId }}" class="visually-hidden">{{ __('Discount filter') }}</label>
    <select id="{{ $offersSelectId }}" wire:model.live.debounce.500ms="offers" class="form-control">
        <option value="all">{{ __('All') }}</option>
        <option value="only-offers">{{ __('Only discounted') }}</option>
    </select>
</div>
