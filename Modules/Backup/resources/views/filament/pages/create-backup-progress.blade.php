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
            sessionId: null,

            async startBackup() {
                while (!this.isCompleted) {
                    try {
                        const response = await $wire.runBackupStep(this.sessionId);
                        console.log('Backup response:', response);
                        if (response && response.success) {
                            this.isCompleted = true;
                            this.currentStep = this.totalSteps;
                            this.downloadUrl = response.downloadUrl;
                            this.exportType = response.export_type;
                            this.filename = response.filename;
                        } else if (response && response.current_step) {
                            this.currentStep = response.current_step;
                            this.totalSteps = response.total_steps;
                            this.percentage = response.percentage;
                        }
                    } catch (error) {
                        console.error('Backup error:', error);
                        break;
                    }
                    await new Promise(resolve => setTimeout(resolve, 1000));
                }
            },

            init() {
                this.$wire.on('backupIsStarted', (data) => {
                    this.sessionId = data.sessionId;
                    console.log('Backup started event received');
                    this.startBackup();
                });
            }
        }));
    </script>
    @endscript

    <div
        x-data="backupProgress()"
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
                    <div class="text-2xl font-bold">Creating Backup</div>
                </div>
                <div x-text="currentStep + ' / ' + totalSteps"></div>

                <div class="relative w-full h-4 bg-gray-200 rounded-full">
                    <div
                        class="absolute h-full bg-blue-500 rounded-full"
                        :style="'width: ' + ((currentStep / totalSteps) * 100) + '%'"
                    ></div>
                </div>

                <div class="mt-4 text-center" x-show="isCompleted">
                    <span class="text-2xl text-green-500 font-medium">Backup completed successfully!</span>
                    <div class="mt-2 mb-4">
                        You can download the backup file from the link below:
                    </div>
                    <div>
                        <div x-show="downloadUrl">
                            <a
                                x-bind:href="downloadUrl"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-500 text-white rounded-lg transition-colors"
                                download
                            >
                                <span class="mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </span>
                                <span x-text="filename ? 'Download ' + filename : 'Download Backup'"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
