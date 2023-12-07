<div>

    <div class="d-flex justify-content-end px-2 py-2">
        <button type="button" class="btn-close" wire:click="$emit('closeModal', true)"></button>
    </div>

    <div class="text-center">
        <h3>
            Campaign Log
        </h3>
    </div>

    <div class="mt-4 px-5 pb-5">
        <div class="row">

            @if($campaignLog)
                @foreach($campaignLog as $log)
                    {{ json_encode($log) }} 
                @endforeach
            @endif

            @if($campaignLog)
            <div class="d-flex justify-content-center mb-3">
                {{ $campaignLog->links("livewire-tables::specific.bootstrap-4.pagination") }}
            </div>
            @endif

        </div>
    </div>
</div>
