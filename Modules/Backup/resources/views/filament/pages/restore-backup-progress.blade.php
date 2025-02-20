<div>


    @script
    <script>
        Alpine.data('restoreProgress', () => ({
            totalSteps: 20,
            currentStep: 0,
            isCompleted: false,
            percentage: 0,
            sessionId: null,
            restoreFile: null,

            async restoreBackup() {
                while (!this.isCompleted) {
                    try {
                        const response = await $wire.runRestoreStep({
                            sessionId: this.sessionId,
                            restoreFile: this.restoreFile,
                        });
                        console.log('Restore response:', response);
                        if (response && response.success) {
                            this.isCompleted = true;
                            this.currentStep = this.totalSteps;
                        } else if (response && response.current_step) {
                            this.currentStep = response.current_step;
                            this.totalSteps = response.total_steps;
                            this.percentage = response.percentage;
                        } else if (response && response.error) {
                            alert('Restore error: ' + response.error);
                            break;
                        }
                    } catch (error) {
                        console.error('Restore error:', error);
                        break;
                    }
                    await new Promise(resolve => setTimeout(resolve, 1000));
                }
            },

            init() {
                this.$wire.on('restoreIsStarted', (data) => {
                    this.sessionId = data.sessionId;
                    this.restoreFile = data.restoreFile;
                    console.log('Restore started event received');
                    this.restoreBackup();
                });
            }
        }));
    </script>
    @endscript

    <div
        x-data="restoreProgress()"
        x-init="init()"

    >
        <div class="flex items-center justify-center px-12 py-12">
            <div class="w-full bg-white p-8 rounded-lg shadow-lg">

                <div class="flex gap-2 items-center mb-4">
                    <div x-show="!isCompleted">
                        @svg('heroicon-o-cog', 'w-12 h-12 text-gray-400 animate-spin')
                    </div>
                    <div x-show="isCompleted">
                        @svg('heroicon-o-cog', 'w-12 h-12 text-gray-400')
                    </div>
                    <div class="text-2xl font-bold">Restoring Backup</div>
                </div>
                <div x-text="currentStep + ' / ' + totalSteps"></div>

                <div class="relative w-full h-4 bg-gray-200 rounded-full">
                    <div
                        class="absolute h-full bg-blue-500 rounded-full"
                        :style="'width: ' + ((currentStep / totalSteps) * 100) + '%'"
                    ></div>
                </div>

                <div class="mt-4 text-center" x-show="isCompleted">
                    <span class="text-2xl text-green-500 font-medium">Restore completed successfully!</span>
                    <div class="mt-2 mb-4">
                        Your backup is restored in the database.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
