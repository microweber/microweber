@php
    $record = $getRecord();
    $history = $record?->statusHistory ?? collect();
    $history = $history->filter(function ($entry) {
        if (!$entry->user_id) return true;
        if (!$entry->user) return false;
        return (bool) $entry->user->is_admin;
    });
@endphp

@if($history->isEmpty())
    <div class="text-sm text-gray-500 dark:text-gray-400 italic">
        No status changes recorded yet.
    </div>
@else
    <div class="space-y-3">
        @foreach($history as $entry)
            @php
                $statusEnum = \Modules\Order\Enums\OrderStatus::tryFrom($entry->new_status);
                $color = match($entry->new_status) {
                    'new' => 'blue',
                    'processing' => 'yellow',
                    'shipped', 'delivered', 'completed' => 'green',
                    'cancelled', 'refunded' => 'red',
                    default => 'gray',
                };
                $icon = match($entry->new_status) {
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'completed' => 'heroicon-m-check-circle',
                    'cancelled' => 'heroicon-m-x-circle',
                    'refunded' => 'heroicon-m-arrow-uturn-left',
                    default => 'heroicon-m-clock',
                };
                $userName = $entry->user ? ($entry->user->first_name
                    ? trim($entry->user->first_name . ' ' . $entry->user->last_name)
                    : $entry->user->email)
                    : 'System';
            @endphp
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20">
                        {{ svg($icon, 'w-4 h-4 text-' . $color . '-600 dark:text-' . $color . '-400') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        @if($entry->old_status)
                            {{ ucfirst($entry->old_status) }} &rarr; {{ ucfirst($entry->new_status) }}
                        @else
                            {{ ucfirst($entry->new_status) }}
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $userName }} &middot; {{ $entry->created_at->diffForHumans() }}
                    </div>
                    @if($entry->note)
                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                            {{ $entry->note }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
