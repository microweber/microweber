<div>

    <div class="modal-header">
        <h5 class="modal-title">Feed Exporting</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">Close</button>
    </div>
    <div class="modal-body">
        <div>

            @if($error)

                <h3>Error! Can't export this feed.</h3>
                <p class="text-danger">{{$error}}</p>

                @else

                @if(!$done)
                <div>
                    <h3>Exporting Content</h3>
                    <h4>Step {{$export_log['current_step']}} of {{$export_log['total_steps']}}</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{$export_log['percentage']}}%;" aria-valuenow="{{$export_log['percentage']}}" aria-valuemin="0" aria-valuemax="100">
                            {{$export_log['percentage']}}%
                        </div>
                    </div>
                </div>
                @else
                    <h3>Done!</h3>
                    <br />
                    <button type="button" wire:click="downloadFile" class="btn btn-outline-success"><i class="fa fa-download"></i> Download</button>
                @endif

            @endif

        </div>
    </div>
    <script>
       setTimeout(function() {
            window.Livewire.emit('exportToolNextStep');
        }, 1000);

        window.addEventListener('nextStepCompleted', event => {
            window.Livewire.emit('exportToolNextStep');
        });
    </script>

    <div class="modal-footer">
    </div>

</div>
