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

                    <div class="card mt-2">
                        <div class="card-body">
                            {{$log->subscriber->name}} - {{$log->subscriber->email}} | Send at: {{$log->created_at}}
                        </div>
                    </div>

                @endforeach
            @endif

            @if($campaignLog)
            <div class="d-flex justify-content-center mb-3 mt-4">
                {{ $campaignLog->links("livewire-tables::specific.bootstrap-4.pagination") }}
            </div>
            @endif

        </div>
    </div>
</div>
