@php
    $record = $getRecord();
    $refunds = $record?->refunds ?? collect();
    $totalRefunded = $refunds->where('status', 'completed')->sum('amount');
    $currency = $record?->currency ?? '';
@endphp

@if($refunds->isEmpty())
    <div class="text-sm text-gray-500 dark:text-gray-400 italic">
        No refunds recorded.
    </div>
@else
    <div class="space-y-3">
        <div class="flex items-center justify-between px-2 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10">
            <span class="text-xs font-medium text-red-700 dark:text-red-400">Total refunded</span>
            <span class="text-sm font-semibold text-red-700 dark:text-red-400">{{ number_format($totalRefunded, 2) }} {{ $currency }}</span>
        </div>

        @foreach($refunds as $refund)
            @php
                $reasonLabels = [
                    'customer_request' => 'Customer request',
                    'defective_product' => 'Defective product',
                    'wrong_item' => 'Wrong item shipped',
                    'not_received' => 'Item not received',
                    'duplicate_order' => 'Duplicate order',
                    'other' => 'Other',
                ];
                $userName = $refund->refundedBy
                    ? (trim(($refund->refundedBy->first_name ?? '') . ' ' . ($refund->refundedBy->last_name ?? '')) ?: $refund->refundedBy->email)
                    : 'System';
                $statusColor = match($refund->status) {
                    'completed' => 'green',
                    'pending' => 'yellow',
                    'failed' => 'red',
                    default => 'gray',
                };
            @endphp
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/20">
                        {{ svg('heroicon-m-arrow-uturn-left', 'w-4 h-4 text-red-600 dark:text-red-400') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($refund->amount, 2) }} {{ $currency }}
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.65rem] font-medium
                            {{ $refund->type === 'full' ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400' }}">
                            {{ ucfirst($refund->type) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $reasonLabels[$refund->reason] ?? $refund->reason }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $userName }} &middot; {{ $refund->created_at->diffForHumans() }}
                    </div>
                    @if($refund->note)
                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1 italic">
                            {{ $refund->note }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
