@php
$currentRecord = $getRecord();
@endphp
<div>

    @if($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_DRAFT)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-gray-600 bg-gray-100 rounded-full dark:text-gray-400 dark:bg-gray-700">
            <x-filament::icon
                icon="heroicon-m-pencil"
                class="h-4 w-4"
            /> {{ __('Draft') }}
        </span>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_PROCESSING)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-blue-700 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900">
            <x-filament::loading-indicator class="h-4 w-4" /> {{ __('Sending') }} ({{$currentRecord->jobs_progress}}%)
        </span>
        <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{$currentRecord->jobs_progress}}%"></div>
        </div>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_FINISHED)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-green-700 bg-green-100 rounded-full dark:text-green-400 dark:bg-green-900">
            <x-filament::icon
                icon="heroicon-m-check-circle"
                class="h-4 w-4"
            /> {{ __('Finished') }}
        </span>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_FAILED)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-red-700 bg-red-100 rounded-full dark:text-red-400 dark:bg-red-900">
            <x-filament::icon
                icon="heroicon-m-x-circle"
                class="h-4 w-4"
            /> {{ __('Failed') }}
        </span>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_SCHEDULED)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-amber-700 bg-amber-100 rounded-full dark:text-amber-400 dark:bg-amber-900">
            <x-filament::icon
                icon="heroicon-m-clock"
                class="h-4 w-4"
            /> {{ __('Scheduled') }}
        </span>
        @if($currentRecord->scheduled_at)
            <div class="mt-1">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $currentRecord->scheduled_at }}
                </span>
            </div>
        @endif

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_CANCELED)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-gray-500 bg-gray-100 rounded-full line-through dark:text-gray-400 dark:bg-gray-700">
            <x-filament::icon
                icon="heroicon-m-no-symbol"
                class="h-4 w-4"
            /> {{ __('Canceled') }}
        </span>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_PENDING)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-blue-700 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900">
            <x-filament::loading-indicator class="h-4 w-4" /> {{ __('Pending') }}
        </span>

    @elseif($currentRecord->status === \Modules\Newsletter\Models\NewsletterCampaign::STATUS_QUEUED)

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-blue-700 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900">
            <x-filament::loading-indicator class="h-4 w-4" /> {{ __('Queued') }}
        </span>

    @elseif($currentRecord->status === 'sending')

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-blue-700 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900">
            <x-filament::loading-indicator class="h-4 w-4" /> {{ __('Sending') }}
        </span>

    @else

        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold leading-5 text-gray-600 bg-gray-100 rounded-full dark:text-gray-400 dark:bg-gray-700">
            {{ ucfirst($currentRecord->status) }}
        </span>

    @endif

</div>
