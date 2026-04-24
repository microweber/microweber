@php
    $logs = $this->logs;
    $stats = $this->stats;
@endphp

<x-filament-panels::page>
    <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
        <div class="text-sm text-gray-600 dark:text-gray-300">
            Job <span class="font-mono">#{{ $recordId }}</span> ·
            <span data-testid="logs-stats-imported">{{ $stats['imported'] }}</span> imported ·
            <span data-testid="logs-stats-staged">{{ $stats['staged'] }}</span> staged ·
            <span data-testid="logs-stats-excluded">{{ $stats['excluded'] }}</span> excluded
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm"
               data-testid="logs-table">
            <thead class="bg-gray-50 dark:bg-gray-800 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-3 py-2 w-24">Status</th>
                    <th class="px-3 py-2">Title</th>
                    <th class="px-3 py-2">GUID</th>
                    <th class="px-3 py-2">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($logs as $row)
                    <tr data-testid="logs-row" data-kind="{{ $row['kind'] }}">
                        <td class="px-3 py-2">
                            @php
                                $badgeClasses = match ($row['kind']) {
                                    'imported' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'staged' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'excluded' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $badgeClasses }}">
                                {{ ucfirst($row['kind']) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $row['title'] ?: '—' }}</td>
                        <td class="px-3 py-2 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $row['guid'] }}</td>
                        <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ $row['detail'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">
                            No logs yet for this job.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</x-filament-panels::page>
