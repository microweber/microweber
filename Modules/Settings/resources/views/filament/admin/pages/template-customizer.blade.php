<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Settings Panel --}}
        <div class="w-full lg:w-1/2 xl:w-5/12">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-paint-brush class="w-5 h-5 text-primary-500"/>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $this->getTitle() }}
                        </h2>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $this->getDescription() }}
                    </p>
                </div>

                <div class="p-6">
                    {{ $this->form }}
                </div>
            </div>

            {{-- Quick Actions --}}
            @if($this->selectedTemplate)
            <div class="mt-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    {{-- task-2026-05-30-tplcust: inline style with !important
                         enforces WCAG 2.5.5 touch-target floor on size="sm"
                         Filament buttons (probed 390x844 — rendered at 36px
                         without the override). Inline style alone loses the
                         cascade against Filament v5's own !important
                         .fi-btn.fi-size-sm min-height rule; the !important
                         flag is mandatory. Tailwind JIT did NOT compile a new
                         min-h-[44px] arbitrary class added to this file. --}}
                    <x-filament::button
                        type="button"
                        wire:click="$dispatch('refreshPreview')"
                        icon="heroicon-m-arrow-path"
                        color="gray"
                        size="sm"
                        style="min-height:44px !important;"
                    >
                        Refresh Preview
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        href="{{ site_url() }}"
                        tag="a"
                        target="_blank"
                        icon="heroicon-m-eye"
                        color="gray"
                        size="sm"
                        style="min-height:44px !important;"
                    >
                        View Live Site
                    </x-filament::button>
                </div>
            </div>
            @endif
        </div>

        {{-- Live Preview Panel --}}
        <div class="w-full lg:w-1/2 xl:w-7/12">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-eye class="w-5 h-5 text-primary-500"/>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Live Preview
                        </h2>
                    </div>

                    @if($this->selectedTemplate)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Template: {{ $this->selectedTemplate }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Live
                        </span>
                    </div>
                    @endif
                </div>

                <div
                    class="p-4 bg-gray-100 dark:bg-gray-800"
                    x-data="{
                        isLoading: true,
                        scale: 0.75,
                        previewUrl: @js($this->previewUrl),
                        init() {
                            this.$wire.on('refreshPreview', () => {
                                this.refreshFrame();
                            });

                            this.$wire.on('templateChanged', ({ template, previewUrl }) => {
                                if (previewUrl) {
                                    this.previewUrl = previewUrl;
                                    this.isLoading = true;
                                }
                            });
                        },
                        refreshFrame() {
                            const frame = this.$refs.previewFrame;
                            if (frame) {
                                this.isLoading = true;
                                frame.src = frame.src;
                            }
                        },
                        onFrameLoad() {
                            this.isLoading = false;
                        }
                    }"
                    wire:ignore
                >
                    <template x-if="previewUrl">
                        <div class="relative">
                            {{-- Loading Spinner --}}
                            <div
                                x-show="isLoading"
                                x-transition.opacity
                                class="absolute inset-0 z-10 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg"
                            >
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
                                    <span class="mt-3 text-sm text-gray-500 dark:text-gray-400">Loading preview...</span>
                                </div>
                            </div>

                            {{-- Preview Controls --}}
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    {{-- task-2026-05-30-tplcust: zoom +/- buttons
                                         lifted to 44x44 (WCAG 2.5.5) from 28x28
                                         via inline style with !important —
                                         Tailwind JIT did NOT compile fresh
                                         min-w-[44px] / min-h-[44px] arbitrary
                                         classes added to this file (pre-existing
                                         min-w-[60px] DID compile, confirming
                                         inline style is the only guaranteed
                                         cascade-winner here). The !important
                                         flag is mandatory because surrounding
                                         Filament admin CSS rules ship !important
                                         declarations on .fi-btn sizing. --}}
                                    <button
                                        type="button"
                                        x-on:click="scale = Math.max(0.5, scale - 0.1)"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                        style="min-width:44px !important;min-height:44px !important;"
                                    >
                                        <x-heroicon-m-minus class="w-4 h-4"/>
                                    </button>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 min-w-[60px] text-center" x-text="Math.round(scale * 100) + '%'">75%</span>
                                    <button
                                        type="button"
                                        x-on:click="scale = Math.min(1.0, scale + 0.1)"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                        style="min-width:44px !important;min-height:44px !important;"
                                    >
                                        <x-heroicon-m-plus class="w-4 h-4"/>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2">
                                    {{-- task-2026-05-30-tplcust: Reload button
                                         inline style with !important enforces
                                         WCAG 2.5.5 — Tailwind JIT compile not
                                         guaranteed on fresh arbitrary classes
                                         added to this file. The !important
                                         flag wins the cascade against
                                         Filament's own !important sizing
                                         rules. --}}
                                    <button
                                        type="button"
                                        x-on:click="refreshFrame()"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 py-1.5 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                        style="min-height:44px !important;"
                                    >
                                        <x-heroicon-m-arrow-path class="w-4 h-4"/>
                                        Reload
                                    </button>
                                </div>
                            </div>

                            {{-- Preview Frame Container --}}
                            <div class="relative overflow-hidden rounded-lg border border-gray-300 dark:border-gray-600 bg-white" style="height: 600px;">
                                <div
                                    class="origin-top-left transition-transform duration-300"
                                    x-bind:style="'transform: scale(' + scale + '); width: ' + (100 / scale) + '%; height: ' + (100 / scale) + '%;'"
                                >
                                    <iframe
                                        x-ref="previewFrame"
                                        x-bind:src="previewUrl"
                                        class="w-full h-full border-0"
                                        sandbox="allow-scripts allow-same-origin allow-forms"
                                        loading="lazy"
                                        x-on:load="onFrameLoad()"
                                        title="Template Preview"
                                    ></iframe>
                                </div>
                            </div>

                            {{-- Device View Toggle --}}
                            {{-- task-2026-05-30-tplcust: device toggle buttons
                                 lifted to 44x44 (WCAG 2.5.5) from 28x28 via
                                 inline style with !important — Tailwind JIT
                                 not guaranteed on fresh arbitrary classes
                                 added to this file, AND Filament ships
                                 !important sizing rules on its own button
                                 selectors that win against bare inline style. --}}
                            <div class="flex items-center justify-center gap-2 mt-4">
                                <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" style="min-width:44px !important;min-height:44px !important;" title="Desktop View">
                                    <x-heroicon-m-computer-desktop class="w-4 h-4"/>
                                </button>
                                <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" style="min-width:44px !important;min-height:44px !important;" title="Tablet View">
                                    <x-heroicon-m-device-tablet class="w-4 h-4"/>
                                </button>
                                <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-1.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" style="min-width:44px !important;min-height:44px !important;" title="Mobile View">
                                    <x-heroicon-m-device-phone-mobile class="w-4 h-4"/>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="!previewUrl">
                        <div class="flex flex-col items-center justify-center h-[600px] text-center">
                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <x-heroicon-o-photo class="w-8 h-8 text-gray-400"/>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Preview Available</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                Select a template from the dropdown above to see a live preview of your changes.
                            </p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Template Info Card --}}
            @if($this->selectedTemplate)
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400"/>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100">Template Information</h3>
                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                            Changes you make here will be applied immediately to the preview.
                            Click "Save" to persist changes to your live site.
                        </p>
                        <div class="mt-3 flex items-center gap-4 text-xs text-blue-600 dark:text-blue-400">
                            <span class="flex items-center gap-1">
                                <x-heroicon-o-bolt class="w-3 h-3"/>
                                Auto-save enabled
                            </span>
                            <span class="flex items-center gap-1">
                                <x-heroicon-o-clock class="w-3 h-3"/>
                                Changes apply in real-time
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
