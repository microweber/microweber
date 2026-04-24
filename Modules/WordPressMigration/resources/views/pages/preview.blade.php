@php
    $rows = $this->rows;
    $stats = $this->stats;
    $previewRow = $this->previewRow;
@endphp

<x-filament-panels::page>
    @if($jobId === null)
        <div class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
            No staged migration found. Run a dry-run import first, then come back to review.
        </div>
    @else
        <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Job <span class="font-mono">#{{ $jobId }}</span> ·
                <span data-testid="preview-stats-total">{{ $stats['total'] }}</span> staged ·
                <span data-testid="preview-stats-excluded">{{ $stats['excluded'] }}</span> excluded
                @if(($stats['failed'] ?? 0) > 0)
                    · <span class="text-red-600 dark:text-red-400 font-medium" data-testid="preview-stats-failed">
                        {{ $stats['failed'] }}
                    </span> failed
                @endif
            </div>
            <div class="flex items-center gap-2">
                <x-filament::button
                    wire:click="bulkIncludeAll"
                    color="gray"
                    icon="heroicon-o-check"
                    size="sm"
                    data-testid="preview-bulk-include">
                    Include all
                </x-filament::button>
                <x-filament::button
                    wire:click="bulkExcludeAll"
                    color="warning"
                    icon="heroicon-o-no-symbol"
                    size="sm"
                    data-testid="preview-bulk-exclude">
                    Exclude all
                </x-filament::button>
                @if(($stats['failed'] ?? 0) > 0)
                    <x-filament::button
                        wire:click="retryFailed"
                        wire:confirm="Retry {{ $stats['failed'] }} failed staging rows?"
                        color="danger"
                        icon="heroicon-o-arrow-path"
                        size="sm"
                        data-testid="preview-retry-failed">
                        Retry failed ({{ $stats['failed'] }})
                    </x-filament::button>
                @endif
                <x-filament::button
                    wire:click="commitStaged"
                    wire:confirm="Commit {{ $stats['total'] - $stats['excluded'] }} staged items to live content? This cannot be undone."
                    color="success"
                    icon="heroicon-o-rocket-launch"
                    size="sm"
                    data-testid="preview-commit">
                    Commit to live
                </x-filament::button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm"
                   data-testid="preview-table">
                <thead class="bg-gray-50 dark:bg-gray-800 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-3 py-2 w-8">Keep?</th>
                        <th class="px-3 py-2">Title</th>
                        <th class="px-3 py-2">GUID</th>
                        <th class="px-3 py-2">Canonical URL</th>
                        <th class="px-3 py-2 w-28"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($rows as $row)
                        <tr class="{{ $row->excluded ? 'opacity-50' : '' }}"
                            data-testid="preview-row-{{ $row->id }}">
                            <td class="px-3 py-2">
                                <input type="checkbox"
                                       wire:click="toggleExcluded({{ $row->id }})"
                                       @checked(!$row->excluded)
                                       data-testid="preview-keep-{{ $row->id }}"
                                       class="rounded border-gray-300">
                            </td>
                            <td class="px-3 py-2 font-medium">
                                {{ $row->title ?: '(untitled)' }}
                            </td>
                            <td class="px-3 py-2 text-xs font-mono text-gray-500">
                                {{ $row->source_guid }}
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-500 truncate max-w-xs">
                                {{ $row->canonical_url }}
                            </td>
                            <td class="px-3 py-2 text-right">
                                <x-filament::button
                                    wire:click="openPreview({{ $row->id }})"
                                    color="gray"
                                    size="xs"
                                    data-testid="preview-view-{{ $row->id }}">
                                    View rendered
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">
                                No staged items yet for this job.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $rows->links() }}
        </div>
    @endif

    @if($previewRow)
        {{-- Rendered preview modal. srcdoc keeps the iframe isolated:
             no network, no cross-frame script access, no chance of
             the preview writing to live content. --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
             data-testid="preview-modal"
             wire:click.self="closePreview">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="font-semibold">{{ $previewRow->title ?: '(untitled)' }}</div>
                    <button wire:click="closePreview"
                            class="text-gray-500 hover:text-gray-800"
                            data-testid="preview-modal-close">&times;</button>
                </div>
                <div class="flex-1 overflow-hidden p-2">
                    <iframe sandbox="allow-same-origin"
                            srcdoc="{{ e((string)$previewRow->content_html) }}"
                            data-testid="preview-iframe"
                            class="w-full h-[70vh] border border-gray-200 dark:border-gray-700 rounded bg-white">
                    </iframe>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
