@php
    $cartItemId = $evaluate(fn ($get) => $get('id'));
    $image = app()->cart_manager->get_cart_item_image($cartItemId);
@endphp

@if ($image)
    <img src="{{ $image }}" alt="Product Image" class="w-16 h-16 object-cover rounded-lg">
@else
    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </div>
@endif
