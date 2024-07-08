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
        let finished = false;
        async function executeNextStep() {
            if (finished) {
                return;
            }
            await $wire.dispatch('execute-next-step');
            setTimeout(() => {
                if (!finished) {
                    executeNextStep();
                }
            }, 1000);
        }

        await executeNextStep();

        $wire.on('campaign-finished', () => {
            finished = true;
        });
    </script>
    @endscript

    <div class="mt-4">

       <div class="p-2 bg-white rounded">
           Start processing campaign...
       </div>

        <div>
            Current step: {{ $step }}
            <br />
            Total steps: {{ $totalSteps }}
        </div>

        @foreach($lastProcessed as $process)
            <div class="p-2 bg-white rounded mt-2">
                @dump($process)
            </div>
        @endforeach

    </div>
    @endif

</div>
