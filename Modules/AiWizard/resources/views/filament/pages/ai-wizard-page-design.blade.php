<x-filament-panels::page>
    <div class="space-y-6">
        <div class="prose max-w-none">
            <h2>Page Design</h2>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <form wire:submit.prevent="submit">
                    {{ $this->form }}

                </form>
            </div>
            <div>
                <livewire:modules.aiwizard::section-processor :record="$record"/>
            </div>

        </div>


    </div>
</x-filament-panels::page>
