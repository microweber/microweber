<form wire:submit.prevent="save">
    <div class="modal-header">
        <h5 class="modal-title">Feed importing</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">Close</button>
    </div>
    <div class="modal-body">
        <div>

            @if($error)

                <h3>Done!</h3>
                <p>{{$error}}</p>

                @else

                @if(!$done)
                <div>
                    <h3>Importing content</h3>
                    <h4>Step {{$import_log['current_step']}} of {{$import_log['total_steps']}}</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{$import_log['percentage']}}%;" aria-valuenow="{{$import_log['percentage']}}" aria-valuemin="0" aria-valuemax="100">
                            {{$import_log['percentage']}}%
                        </div>
                    </div>
                </div>
                @else
                    <h3>Done!</h3>
                @endif

            @endif

        </div>
    </div>
    <script>
       setTimeout(function() {
            window.Livewire.emit('importExportToolNextStep');
        }, 1000);

        window.addEventListener('nextStepCompleted', event => {
            window.Livewire.emit('importExportToolNextStep');
        });
    </script>

    <div class="modal-footer">
    </div>
</form>
