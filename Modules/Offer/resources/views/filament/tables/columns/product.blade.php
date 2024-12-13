@php
    $record = $getRecord();



    if (!$record) {
        return;
    }

    $product =  \Modules\Product\Models\Product::query()->where('id', $record->product_id)->first();
    if (!$product) {
        return;
    }
@endphp

<div class="flex items-center gap-4">
    @if($product->image)
        <img src="{{ $product->image }}" alt="{{ $product->title }}" class="w-10 h-10 rounded-lg object-cover">
    @endif
    <div class="flex flex-col">
        <span class="text-sm font-medium">{{ $product->title }}</span>
        <span class="text-xs text-gray-500">{{ currency_format($product->price) }}</span>
    </div>
</div>
