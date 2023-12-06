<div>

    <div class="d-flex justify-content-end px-2 py-2">
        <button type="button" class="btn-close" wire:click="$emit('closeModal', true)"></button>
    </div>

    <div class="text-center">
        <h3>
           Process Campaigns
        </h3>
    </div>

    <div class="mt-4 px-5 pb-5">

        <div wire:pool>
         {!! $this->log !!}
        </div>

    </div>

</div>
