@php
$randId = 'shpr'.md5($this->moduleId . $this->moduleType);
@endphp
<div wire:ignore>
    <div class="my-4">
        <div class="mb-2">
            Price range
        </div>
        <div class="form-range mt-1" id="js-shop-price-range-{{$randId}}"></div>
       <div class="d-flex gap-4 mt-4">

           <div>
               <label>From</label>
               <div class="input-group">
                   <span class="input-group-text">$</span>
                   <input type="text" class="form-control"  wire:model="priceFrom" id="js-shop-price-range-from-{{$randId}}" aria-label="Amount (to the nearest dollar)">
               </div>
           </div>
           <div>
               <label>To</label>
               <div class="input-group">
                   <span class="input-group-text">$</span>
                   <input type="text" class="form-control" wire:model="priceTo" id="js-shop-price-range-to-{{$randId}}" aria-label="Amount (to the nearest dollar)">
               </div>
           </div>
       </div>
    </div>

   @include('shop::filters.price_range.script', [
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
