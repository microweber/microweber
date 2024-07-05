<div>

    <div class="text-center">
        <h3>
            Process Campaigns
        </h3>
    </div>

    <div class="mt-4 px-5 pb-5">

        @script
        <script>

            let runningProcessCampaigns = true;
            $wire.dispatch('processCampaigns');
            let log = document.getElementById('js-process-campaigns-log');
            log.innerHTML = 'Processing...';

            let processCampaignInterval = setInterval(function() {
                fetch('{{$logPublicUrl}}').then(response => response.text()).then(data => {
                    log.innerHTML = data;
                    if (data.indexOf('Process Campaigns Complete') !== -1) {
                        runningProcessCampaigns = false;
                        clearInterval(processCampaignInterval);
                    }
                });
            }, 3000);

        </script>
        @endscript

        <div id="js-process-campaigns-log"></div>

    </div>

</div>
