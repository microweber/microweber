<div>

    <div class="flex justify-center items-center w-full h-full">
    <div x-data="{
        name: @entangle('name'),
    }" class="my-24 w-[36rem] m-auto">


         <h3 class="text-xl mb-4">
            Creating a new email marketing campaign
        </h3>

        <div class="mb-4">
            <input class="border-transparent border-b-1 border-b-gray-500/30 bg-white p-0 px-4 rounded-md py-4 w-full text-xl"
                   placeholder="Enter campaign name" required="required" type="text"
                   wire:keydown.enter="createCampaign"
                   wire:model.live="name">
        </div>

        <div x-show="name.length > 0" class="mb-4">

            <x-filament::button wire:click="createCampaign" size="xl" color="primary">
                Create Campaign
            </x-filament::button>

        </div>


    </div>
    </div>

</div>
