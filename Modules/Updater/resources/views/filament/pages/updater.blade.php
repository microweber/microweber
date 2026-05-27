{{-- task-2026-05-27-6e6957 / AI-1113 --}}
<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center gap-y-8 p-4">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Microweber Updater</h2>
            <p class="mt-2 text-gray-700 dark:text-gray-300">Current Version: <span class="font-semibold text-gray-900 dark:text-white">{{ $currentVersion }}</span></p>
            <p class="mt-1 text-gray-700 dark:text-gray-300">Latest Stable: <span class="font-semibold text-gray-900 dark:text-white">{{ $latestVersion }}</span></p>

            @if($updateAvailable)
                <div class="mt-4 p-4 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-lg">
                    <p>A new version is available for download!</p>
                </div>
            @elseif($aheadOfLatest)
                <div class="mt-4 p-4 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-lg">
                    <p>You are running a development version ahead of the latest stable release.</p>
                </div>
            @else
                <div class="mt-4 p-4 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-lg">
                    <p>Your system is up to date.</p>
                </div>
            @endif
        </div>

        @if (!$canUpdate && count($updateMessages) > 0)
            <div class="w-full max-w-lg p-4 border rounded-lg border-warning-500 bg-warning-50 dark:bg-gray-800 dark:border-warning-700">
                <h3 class="mb-2 text-lg font-medium text-warning-700 dark:text-warning-400">Update Requirements</h3>
                <ul class="pl-5 mt-2 gap-y-1 list-disc text-warning-700 dark:text-warning-400">
                    @foreach ($updateMessages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full max-w-md">
            <div class="flex flex-col items-center gap-y-4">
                <div class="w-full">
                    <label for="branch-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Branch</label>
                    <select id="branch-select" wire:change="changeBranch($event.target.value)" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600">
                        @foreach($branches as $key => $label)
                            <option value="{{ $label }}" {{ $selectedBranch == $label ? 'selected' : '' }}>{{ \Illuminate\Support\Str::headline($label) }}</option>
                        @endforeach
                    </select>
                </div>

                @if($updateAvailable)
                    <button
                        wire:click="startUpdate"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ !$canUpdate ? 'disabled' : '' }}
                    >
                        Update Now to v{{ $latestVersion }}
                    </button>
                @endif
            </div>
        </div>

        <div class="w-full max-w-lg p-4 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100">Update Information</h3>
            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                <p>The update process will:</p>
                <ol class="pl-5 mt-2 gap-y-1 list-decimal">
                    <li>Download the latest version from the Microweber server</li>
                    <li>Extract the files to a temporary directory</li>
                    <li>Replace the current files with the new ones</li>
                    <li>Clean up temporary files</li>
                </ol>
                <p class="mt-4">During the update process, your website will be temporarily unavailable. Make sure to backup your website before updating.</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
