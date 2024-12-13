<div class="flex items-center gap-2">
    @if($image)
        <img src="{{ $image }}" alt="{{ $title }}" class="w-8 h-8 object-cover rounded-full">
    @endif
    <div>
        <div class="font-medium">{{ $title }}</div>
        <div class="text-sm text-gray-500">{{ $price }}</div>
    </div>
</div>
