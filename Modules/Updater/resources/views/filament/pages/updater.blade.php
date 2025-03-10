<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">System Update</h2>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $updateAvailable ? 'bg-warning-500 text-white' : 'bg-success-500 text-white' }}">
                        {{ $updateAvailable ? 'Update Available' : 'Up to Date' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-medium">Current Version</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold">{{ $currentVersion }}</span>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-medium">Latest Version</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold">{{ $latestVersion }}</span>
                    </div>
                </div>
            </div>

            @if (!$canUpdate && count($updateMessages) > 0)
                <div class="p-4 mt-6 border rounded-lg border-warning-500 bg-warning-50 dark:bg-gray-800 dark:border-warning-700">
                    <h3 class="mb-2 text-lg font-medium text-warning-700 dark:text-warning-400">Update Requirements</h3>
                    <ul class="pl-5 mt-2 space-y-1 list-disc text-warning-700 dark:text-warning-400">
                        @foreach ($updateMessages as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6 mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-medium">Update Instructions</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p>The update process will:</p>
                    <ol class="pl-5 mt-2 space-y-1 list-decimal">
                        <li>Download the latest version from the Microweber server</li>
                        <li>Extract the files to a temporary directory</li>
                        <li>Replace the current files with the new ones</li>
                        <li>Clean up temporary files</li>
                    </ol>
                    <p class="mt-4">During the update process, your website will be temporarily unavailable. Make sure to backup your website before updating.</p>
                </div>
            </div>

            <div class="p-6 mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-medium">Standalone Updater</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p>If you prefer to update your website without using the admin panel, you can use the standalone updater.</p>
                    <p>Click the "Copy Standalone Updater" button to create a standalone updater file in your public directory.</p>
                    <p>You can then access the standalone updater at <code>https://your-site.com/standalone-updater.php</code></p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
