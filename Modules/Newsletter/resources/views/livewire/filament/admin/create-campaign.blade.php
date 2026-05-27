<div>

    <div class="flex justify-center items-center w-full h-full">
    <div x-data="{
        name: @entangle('name'),
    }" class="my-24 w-[36rem] m-auto">

        <h3 class="text-xl mb-4">
            Creating a new email marketing campaign
        </h3>

        <div class="mb-4">
            <label for="mw-newsletter-campaign-name" class="sr-only">Campaign name</label>
            <input id="mw-newsletter-campaign-name"
                   class="border-transparent border-b-1 border-b-gray-500/30 bg-white p-0 px-4 rounded-md py-4 w-full text-xl"
                   placeholder="Enter your campaign name..." required="required" type="text"
                   aria-label="Campaign name"
                   wire:keydown.enter="createCampaign"
                   wire:model.live.debounce.500ms="name">
        </div>

        <div class="mb-4">

            <x-filament::button wire:click="createCampaign" size="xl" color="primary" :disabled="false">
                Create Campaign
            </x-filament::button>

        </div>

    </div>
    </div>

</div>
