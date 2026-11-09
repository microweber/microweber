<x-filament-panels::page>
    @php($sites = $this->getSites())

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3 text-start font-medium">Domain</th>
                        <th class="px-4 py-3 text-start font-medium">Database</th>
                        <th class="px-4 py-3 text-start font-medium">Status</th>
                        <th class="px-4 py-3 text-end font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($sites as $site)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-950 dark:text-white">{{ $site['domain'] }}</span>
                                    @if ($site['is_main'])
                                        <span class="fi-badge rounded-md bg-primary-50 px-1.5 py-0.5 text-xs font-medium text-primary-700 ring-1 ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400">Main site</span>
                                    @else
                                        <span class="fi-badge rounded-md bg-gray-100 px-1.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 dark:bg-white/10 dark:text-gray-300">Subsite</span>
                                    @endif
                                </div>
                                <a href="{{ $site['app_url'] }}" target="_blank" rel="noopener"
                                   class="text-xs text-primary-600 hover:underline dark:text-primary-400">{{ $site['app_url'] }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <div class="font-mono text-xs">{{ $site['db_database'] ?: '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $site['db_host'] ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($site['installed'])
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-success-600 dark:text-success-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span> Installed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-warning-600 dark:text-warning-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-warning-500"></span> Not installed
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @unless ($site['is_main'])
                                        <x-filament::button size="sm" color="gray" icon="heroicon-m-pencil-square"
                                            wire:click="mountAction('editSite', @js(['domain' => $site['domain']]))">
                                            Edit
                                        </x-filament::button>
                                        @unless ($site['installed'])
                                            <x-filament::button size="sm" color="primary" icon="heroicon-m-circle-stack"
                                                wire:click="mountAction('installSite', @js(['domain' => $site['domain']]))">
                                                Install
                                            </x-filament::button>
                                        @endunless
                                        <x-filament::button size="sm" color="danger" icon="heroicon-m-trash"
                                            wire:click="mountAction('deleteSite', @js(['domain' => $site['domain']]))">
                                            Delete
                                        </x-filament::button>
                                    @else
                                        <span class="text-xs text-gray-400">Managed via base .env</span>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
