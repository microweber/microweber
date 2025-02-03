<div>


    <div
        x-data="{
            maxSteps: 20,
            currentStep: 0
        }"
    >

        <div class="flex items-center justify-center py-12">
            <div class="w-1/2 bg-white p-8 rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold">Creating Backup</h1>
                    <span x-text="currentStep + 1 + ' / ' + maxSteps"></span>
                </div>

                <div class="relative w-full h-4 bg-gray-200 rounded-full">
                    <div
                        class="absolute h-full bg-blue-500 rounded-full"
                        :style="'width: ' + ((currentStep / maxSteps) * 100) + '%'"
                    ></div>
                </div>
            </div>
        </div>

    </div>

</div>
