<div>

    <div>
        <h2 class="text-xl font-weight-light">
            Process Campaign - {{ $campaign->name }}
        </h2>
    </div>

    @if($this->finished)
        <div class="mt-4">
            <div class="p-4 bg-green-500 text-white rounded text-center">
                <h3 class="text-2xl">
                    Campaign has been finished.
                </h3>
                <br />
               <p>
                   All emails have been sent successfully.
               </p>
            </div>
        </div>
    @else

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

        $wire.on('campaign-finished', () => {
            finished = true;
        });
    </script>
    @endscript

    <div class="mt-4">

       <div class="p-2 bg-white rounded">
           Start processing campaign...
       </div>

        @foreach($lastProcessed as $process)
            <div class="p-2 bg-white rounded mt-2">
                {{ $process }}
            </div>
        @endforeach

    </div>
    @endif

</div>
