<div class="space-y-4 p-4">
    @forelse($values as $field)
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $field['field_name'] }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{!! $field['field_value'] !!}</dd>
        </div>
    @empty
        <p class="text-sm text-gray-500">No form data available.</p>
    @endforelse

    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-400">
            Submitted: {{ $record->created_at?->format('M d, Y H:i') }}
            @if($record->user_ip) &middot; IP: {{ $record->user_ip }} @endif
        </p>
    </div>
</div>
