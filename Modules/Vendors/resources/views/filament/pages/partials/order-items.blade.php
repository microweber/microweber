<div class="fi-ta-text text-sm">
    @if (empty($items))
        <p class="text-gray-500">{{ __('No items.') }}</p>
    @else
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-2 pr-4">{{ __('Product') }}</th>
                    <th class="py-2 pr-4">{{ __('Qty') }}</th>
                    <th class="py-2">{{ __('Price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $item['title'] }}</td>
                        <td class="py-2 pr-4">{{ $item['qty'] }}</td>
                        <td class="py-2">{!! currency_format($item['price']) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
