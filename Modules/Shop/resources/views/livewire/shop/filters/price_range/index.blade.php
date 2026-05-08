@php
$randId = 'shpr'.md5($moduleId . $moduleType);
@endphp
<div wire:ignore>
    <div class="my-4 mw-shop-filter-price-range">
        {{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): heading
             semantics — the bare div was unreachable via h-key
             screen-reader navigation. h3 mirrors the other
             filter sections. --}}
        <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Price range') }}</h3>
        <div class="form-range mt-1" id="js-shop-price-range-{{$randId}}"></div>
       <div class="d-flex gap-4 mt-4">

           {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB finding #8:
                price-range inputs were `type="text"` — admin could type
                non-numeric values (e.g. "abc" or "-50") which posted
                unsanitised to the Livewire backend. type=number +
                inputmode=decimal + min=0 + step=any gives the correct
                mobile keyboard, blocks negative input, and accepts cents. --}}
           <div>
               <label for="js-shop-price-range-from-{{$randId}}">From</label>
               <div class="input-group">
                   <span class="input-group-text">$</span>
                   <input type="number" inputmode="decimal" min="0" step="any" class="form-control" wire:model.live="priceFrom" id="js-shop-price-range-from-{{$randId}}" aria-label="Amount (to the nearest dollar)">
               </div>
           </div>
           <div>
               <label for="js-shop-price-range-to-{{$randId}}">To</label>
               <div class="input-group">
                   <span class="input-group-text">$</span>
                   <input type="number" inputmode="decimal" min="0" step="any" class="form-control" wire:model.live="priceTo" id="js-shop-price-range-to-{{$randId}}" aria-label="Amount (to the nearest dollar)">
               </div>
           </div>
       </div>
    </div>

   @include('modules.shop::livewire.shop.filters.price_range.script', [
    'randId' => $randId,
    'minPrice' => $minPrice,
    'maxPrice' => $maxPrice,
    'priceFrom' => $priceFrom,
    'priceTo' => $priceTo,
    'priceRangeElement'=> 'js-shop-price-range-'.$randId,
    'priceFromElementId' => 'js-shop-price-range-from-'.$randId,
    'priceToElementId' => 'js-shop-price-range-to-'.$randId,
])
</div>
