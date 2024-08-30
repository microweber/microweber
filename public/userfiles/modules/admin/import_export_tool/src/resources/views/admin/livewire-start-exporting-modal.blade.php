<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog" role="document">
            <div class="mw-modal-content">
                <div class="mw-modal-header">
                    <h5 class="mw-modal-title">Feed Exporting</h5>
                    <button type="button" class="btn btn-link" @click="$dispatch('closeModal')">Close</button>
                </div>
                <div class="mw-modal-body">

                    @if($error)

                        <h3>Error! Can't export this feed.</h3>
                        <p class="text-danger">{{$error}}</p>

                    @else

                        @if(!$done)
                            <div>
                                <h3>Exporting Content</h3>
                                <h4>Step {{$export_log['current_step']}} of {{$export_log['total_steps']}}</h4>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$export_log['percentage']}}%;"
                                         aria-valuenow="{{$export_log['percentage']}}" aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{$export_log['percentage']}}%
                                    </div>
                                </div>
                            </div>
                        @else
                            <h3>Done!</h3>
                            <br/>
                            <a href="{{$download_file}}" target="_new" class="btn btn-outline-success"><i
                                    class="fa fa-download"></i> Download</a>
                        @endif

                    @endif

                </div>
            </div>
            <script>
                setTimeout(function () {
                    window.Livewire.dispatch('exportToolNextStep');
                }, 1000);

                window.addEventListener('nextStepCompleted', event => {
                    window.Livewire.dispatch('exportToolNextStep');
                });
            </script>

        </div>
    </div>
</div>
