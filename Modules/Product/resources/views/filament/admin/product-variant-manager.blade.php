<div>
    @if($this->productId > 0)
        <div class="space-y-6">
            {{-- Attribute Selection --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                            Variant Attributes
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Select which attributes define your product variants (e.g., Size, Color).
                        </p>
                    </div>
                    <a href="{{ route('filament.admin.resources.product-variant-attributes.index') }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400">
                        <x-heroicon-m-cog-6-tooth class="h-4 w-4" />
                        Manage Attributes
                    </a>
                </div>

                @php
                    $availableAttributes = $this->getAvailableAttributes();
                @endphp

                @if(empty($availableAttributes))
                    <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center dark:border-gray-700">
                        <x-heroicon-o-swatch class="mx-auto h-8 w-8 text-gray-400" />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            No variant attributes have been created yet.
                        </p>
                        <a href="{{ route('filament.admin.resources.product-variant-attributes.create') }}"
                           target="_blank"
                           class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500">
                            <x-heroicon-m-plus class="h-4 w-4" />
                            Create Attribute
                        </a>
                    </div>
                @else
                    <div class="flex flex-wrap gap-3">
                        @foreach($availableAttributes as $attrId => $attrLabel)
                            <label class="relative flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 transition
                                {{ in_array($attrId, $selectedAttributeIds)
                                    ? 'border-primary-500 bg-primary-50 ring-1 ring-primary-500 dark:border-primary-400 dark:bg-primary-950'
                                    : 'border-gray-300 bg-white hover:border-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-gray-500' }}">
                                <input type="checkbox"
                                       wire:model.live.debounce.500ms="selectedAttributeIds"
                                       value="{{ $attrId }}"
                                       class="sr-only">
                                <x-heroicon-m-check-circle
                                    class="h-5 w-5 {{ in_array($attrId, $selectedAttributeIds) ? 'text-primary-500 dark:text-primary-400' : 'text-gray-300 dark:text-gray-600' }}" />
                                <span class="text-sm font-medium {{ in_array($attrId, $selectedAttributeIds) ? 'text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $attrLabel }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @if(!empty($selectedAttributeIds))
                        <div class="mt-4 flex items-center gap-3">
                            <button type="button"
                                    wire:click="generateCombinations"
                                    wire:loading.attr="disabled"
                                    class="fi-btn fi-btn-size-md relative inline-grid grid-flow-col items-center justify-center gap-1.5 rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm outline-none transition duration-75 hover:bg-primary-500 focus-visible:ring-2 dark:bg-primary-500 dark:hover:bg-primary-400 disabled:opacity-70">
                                <wire:loading wire:target="generateCombinations">
                                    <x-heroicon-m-arrow-path class="h-4 w-4 animate-spin" />
                                </wire:loading>
                                <wire:loading.remove wire:target="generateCombinations">
                                    <x-heroicon-m-bolt class="h-4 w-4" />
                                </wire:loading.remove>
                                Generate Combinations
                            </button>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Creates all possible variant combinations from selected attributes.
                            </span>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Combinations Table --}}
            <div>
                {{ $this->table }}
            </div>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
            <x-heroicon-o-cube class="mx-auto h-10 w-10 text-gray-400" />
            <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Save the product first</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                You need to save the product before you can add variant combinations.
            </p>
        </div>
    @endif
</div>
