<x-filament-panels::page>
    <div class="space-y-6">
        <div class="prose max-w-none">
            <h2>Page Design</h2>
        </div>

            <div>
                <form wire:submit.prevent="submit">
                    {{ $this->form }}

                </form>
            </div>
            <div>
                <livewire:modules.aiwizard::section-processor :record="$record"/>
            </div>




    </div>
</x-filament-panels::page>
