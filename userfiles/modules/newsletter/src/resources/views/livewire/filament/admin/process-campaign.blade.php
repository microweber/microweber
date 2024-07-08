<div>

    <div>
        <h3 class="text-xl font-weight-light">
            Process Campaign - {{ $campaign->name }}
        </h3>
    </div>

    @script
    <script>
        let processing = false;
        let finished = false;
        async function startProcessingCampaign() {
            await $wire.dispatch('start-processing-campaign');
            processing = true;
            await executeNextStep();
        }
        async function executeNextStep() {
            await $wire.dispatch('execute-next-step');
            setTimeout(() => {
                if (!finished) {
                    executeNextStep();
                }
            }, 1000);
        }
        await startProcessingCampaign();
    </script>
    @endscript

    <div class="mt-4">

       <div class="p-2 bg-white rounded">
           Start processing campaign...
       </div>

    </div>

</div>
