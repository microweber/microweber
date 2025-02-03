<div>
    @script
    <script>
        Alpine.data('backupProgress', () => ({
            totalSteps: 20,
            currentStep: 0,
            isCompleted: false,
            percentage: 0,
            downloadUrl: null,
            exportType: null,
            filename: null,

            async startBackup() {
                while (!this.isCompleted && this.currentStep < this.totalSteps) {
                    try {
                        const response = await $wire.runBackupStep();
                        console.log('Backup response:', response);
                        if (response && response.success) {
                            this.isCompleted = true;

                        } else if (response && response.current_step) {
                            this.currentStep = response.current_step;
                            this.totalSteps = response.total_steps;
                            this.percentage = response.percentage;
                            this.downloadUrl = response.download_url;
                            this.exportType = response.export_type;
                            this.filename = response.filename;
                        }
                    } catch (error) {
                        console.error('Backup error:', error);
                        break;
                    }
                    await new Promise(resolve => setTimeout(resolve, 1000));
                }
            },

            init() {
                Livewire.on('backupIsStarted', () => {
                    alert('Backup started!');
                    this.startBackup();
                });
            }
        }));
    </script>
    @endscript

    <div
        x-data="backupProgress"
        x-init="init"

    >
        <div class="flex items-center justify-center py-12">
            <div class="w-1/2 bg-white p-8 rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold">Creating Backup</h1>
                    <span x-text="currentStep + 1 + ' / ' + totalSteps"></span>
                </div>

                <div class="relative w-full h-4 bg-gray-200 rounded-full">
                    <div
                        class="absolute h-full bg-blue-500 rounded-full"
                        :style="'width: ' + ((currentStep / totalSteps) * 100) + '%'"
                    ></div>
                </div>

                <div class="mt-4 text-center" x-show="isCompleted">
                    <span class="text-2xl text-green-500 font-medium">Backup completed successfully!</span>
                    <div>
                        You can download the backup file from the link below:
                        <a
                            x-bind:href="downloadUrl"
                            class="block mt-2 text-blue-500 underline"
                            x-text="filename"
                        >
                                Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
