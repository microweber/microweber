@php
    $stats = $this->progressStats;
    $pollInterval = \Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ViewWordPressMigration::POLL_INTERVAL_SECONDS;
@endphp

<x-filament-panels::page>
    {{ $this->content }}

    <div
        class="fi-section mt-4 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
        data-testid="progress-panel"
        @if($stats['should_poll'])
            wire:poll.{{ $pollInterval }}s="refreshProgress"
        @endif
    >
        <div class="flex items-center justify-between gap-3 border-b border-gray-950/5 px-6 py-4 dark:border-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                Live progress
            </h3>
            <span
                class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium
                @if($stats['is_running'])
                    bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200
                @else
                    bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                @endif
                "
                data-testid="progress-status"
            >
                {{ $stats['is_running'] ? 'Polling every ' . $pollInterval . 's' : 'Idle' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-4 px-6 py-5 sm:grid-cols-4">
            <div>
                <div class="text-xs uppercase tracking-wide text-gray-500">Processed</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100"
                     data-testid="progress-processed">
                    {{ number_format($stats['processed']) }}
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-gray-500">Total</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100"
                     data-testid="progress-total">
                    {{ $stats['total'] !== null ? number_format($stats['total']) : '—' }}
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-gray-500">Failed</div>
                <div class="mt-1 text-2xl font-semibold
                    @if($stats['failed'] > 0) text-red-600 dark:text-red-400
                    @else text-gray-900 dark:text-gray-100
                    @endif"
                    data-testid="progress-failed">
                    {{ number_format($stats['failed']) }}
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-gray-500">ETA</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100"
                     data-testid="progress-eta">
                    {{ $stats['eta_human'] ?? '—' }}
                </div>
            </div>
        </div>

        @if($stats['total'] !== null && $stats['total'] > 0)
            <div class="px-6 pb-5">
                <div class="mb-1 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $stats['percentage'] ?? 0 }}%</span>
                    <span>{{ number_format($stats['processed']) }} / {{ number_format($stats['total']) }}</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div
                        class="h-full rounded-full bg-primary-500 transition-[width] duration-500 ease-out"
                        style="width: {{ $stats['percentage'] ?? 0 }}%"
                        data-testid="progress-bar"
                    ></div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
