@php
    $summary = $this->getProbeSummary();
@endphp

<x-filament-panels::page>
    <form wire:submit.prevent="check">
        {{ $this->form }}
    </form>

    {{-- Offline WXR path: upload a WordPress export (.xml) and import
         directly without a probe. The outer data-testid gives the Dusk
         suite a stable anchor so the test isn't coupled to FilePond's
         internal DOM. --}}
    <div class="mt-6 rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-900"
         data-testid="wxr-upload-section">
        <div class="text-sm font-semibold mb-1">Or upload a WXR file</div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
            WordPress eXtended RSS export (<span class="font-mono">.xml</span>). Imports directly — no probe needed.
        </p>

        <input type="file"
               wire:model="wxrUpload"
               accept=".xml,application/xml,text/xml"
               data-testid="wxr-upload-input"
               class="block text-sm text-gray-600 dark:text-gray-300" />

        <div class="mt-2 text-sm text-gray-500" wire:loading wire:target="wxrUpload">
            Uploading…
        </div>

        @if($wxrUpload)
            <div class="mt-3 flex items-center gap-3">
                <x-filament::button
                    wire:click="importWxrFile"
                    wire:confirm="Import this WXR file into Microweber? Existing rows with matching WP post ids will be updated."
                    color="primary"
                    icon="heroicon-o-document-arrow-up"
                    data-testid="wxr-import-button">
                    Import WXR
                </x-filament::button>
                <span class="text-sm text-gray-500 font-mono">{{ $wxrUpload->getClientOriginalName() }}</span>
            </div>
        @endif
    </div>

    @if($summary)
        <div class="mt-6 space-y-4">
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-900">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Source</span>
                    <span class="font-mono text-sm">{{ $summary['source_url'] }}</span>
                    <span class="px-2 py-0.5 text-xs rounded-full
                        @if($summary['status'] === 'ready') bg-green-100 text-green-800
                        @elseif($summary['status'] === 'running') bg-blue-100 text-blue-800
                        @elseif($summary['status'] === 'finished') bg-emerald-100 text-emerald-800
                        @elseif($summary['status'] === 'failed' || $summary['status'] === 'unreachable') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($summary['status']) }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Detected capabilities</div>
                    @if(empty($summary['capabilities']))
                        <span class="text-sm text-gray-500">None</span>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($summary['capabilities'] as $capability)
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded',
                                    'bg-blue-100 text-blue-800' => $capability === $summary['mode'],
                                    'bg-gray-100 text-gray-700' => $capability !== $summary['mode'],
                                ])>
                                    {{ strtoupper($capability) }}
                                    @if($capability === $summary['mode']) &middot; recommended @endif
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Estimated posts</div>
                        <div class="text-2xl font-semibold">
                            {{ $summary['estimated_posts'] ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Estimated pages</div>
                        <div class="text-2xl font-semibold">
                            {{ $summary['estimated_pages'] ?? '—' }}
                        </div>
                    </div>
                </div>

                @if($summary['rest_namespace'])
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        REST namespace: <span class="font-mono">{{ $summary['rest_namespace'] }}</span>
                    </div>
                @endif
                @if($summary['sitemap_index_url'])
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Sitemap: <span class="font-mono">{{ $summary['sitemap_index_url'] }}</span>
                    </div>
                @endif

                @foreach($summary['warnings'] as $warning)
                    <div class="text-sm text-amber-700 bg-amber-50 border-l-4 border-amber-400 p-2 mt-2">
                        {{ $warning }}
                    </div>
                @endforeach
                @foreach($summary['errors'] as $error)
                    <div class="text-sm text-red-700 bg-red-50 border-l-4 border-red-400 p-2 mt-2">
                        {{ $error }}
                    </div>
                @endforeach
            </div>

            @if($summary['is_usable'])
                <div class="flex items-center gap-3">
                    <x-filament::button
                        wire:click="startImport"
                        wire:confirm="Start importing from {{ $summary['source_host'] }}? Re-running a probe is always safe, but starting an import will begin writing content into Microweber."
                        color="primary"
                        icon="heroicon-o-rocket-launch">
                        Start import
                    </x-filament::button>
                    <span class="text-sm text-gray-500">
                        Ready to pull
                        @if($summary['estimated_posts']) {{ $summary['estimated_posts'] }} posts @endif
                        @if($summary['estimated_posts'] && $summary['estimated_pages']) and @endif
                        @if($summary['estimated_pages']) {{ $summary['estimated_pages'] }} pages @endif
                        via {{ strtoupper($summary['mode']) }}.
                    </span>
                </div>
            @endif
        </div>
    @endif
</x-filament-panels::page>
