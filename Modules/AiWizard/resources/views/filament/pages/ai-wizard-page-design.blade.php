<x-filament-panels::page>
    <div class="space-y-6">
        <div class="prose max-w-none">
            <h2>Page Design</h2>
            <p>Process each section of your page content to generate markdown and HTML versions. You can process sections individually or all at once.</p>
        </div>

        <livewire:modules.aiwizard::section-processor :record="$record" />
    </div>
</x-filament-panels::page>
