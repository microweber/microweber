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

        <script>
            let runningProcessCampaigns = false;
            document.getElementById('js-process-campaigns').addEventListener('click', function() {
                runningProcessCampaigns = true;
                window.livewire.emit('processCampaigns');
                let log = document.getElementById('js-process-campaigns-log');
                log.innerHTML = 'Processing...';

                setInterval(function() {
                    $.ajax('{{$logPublicUrl}}').done(function(data) {
                        log.innerHTML = data;
                        if (data.indexOf('Process Campaigns Complete') !== -1) {
                            runningProcessCampaigns = false;
                        }
                    });
                }, 3000);

            });
        </script>

        <div id="js-process-campaigns-log"></div>

        <div class="text-center">
            <button type="button" id="js-process-campaigns" class="btn btn-outline-primary">
                Run Process Campaigns
            </button>
        </div>

    </div>

</div>
