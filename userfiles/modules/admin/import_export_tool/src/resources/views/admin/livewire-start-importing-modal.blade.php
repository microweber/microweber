<div>

    <div class="modal-header">
        <h5 class="modal-title">Feed Importing</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">Close</button>
    </div>
    <div class="modal-body">
        <div>

            @if($error)

                <h3>Error! Can't import this feed.</h3>
                <p class="text-danger">{{$error}}</p>

                <script>
                    window.preventWindowClose = false;
                </script>

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
                    <br />
                    <button type="button" wire:click="$emit('viewReportAndCloseModal')" class="btn btn-outline-success">View Report</button>
                    <script>
                        window.preventWindowClose = false;
                    </script>
                @endif

            @endif

        </div>
    </div>
    <script>
       setTimeout(function() {
           window.preventWindowClose = true;
            window.Livewire.emit('importExportToolNextStep');
        }, 1000);

        window.addEventListener('nextStepCompleted', event => {
            window.Livewire.emit('importExportToolNextStep');
        });
    </script>

    <script>
        window.preventWindowClose = false;
        window.addEventListener('beforeunload', function(e) {
            if (!window.preventWindowClose) return;
            // Cancel the event
            e.preventDefault();
            // Chrome requires returnValue to be set
            e.returnValue = '';
        });
    </script>

    <div class="modal-footer">
    </div>

</div>
