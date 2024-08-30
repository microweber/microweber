@php
$currentRecord = $getRecord();
@endphp
<div>


    @if($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_DRAFT)

        <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">
            {{ __('Draft') }}
        </span>

    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_PROCESSING)

        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-yellow-200 rounded-full">
            <x-filament::loading-indicator class="h-5 w-5" /> {{ __('Processing') }} ({{$currentRecord->jobs_progress}}%)
        </span>
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{$currentRecord->jobs_progress}}%"></div>
            </div>

    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_FINISHED)

        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-green-500 bg-green-200 rounded-full">
           <x-filament::icon
               icon="heroicon-m-check"
               class="h-5 w-5 text-green-500"
           /> {{ __('Finished') }}
        </span>

    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_FAILED)

        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-red-500 bg-red-200 rounded-full">
           <x-filament::icon
               icon="heroicon-m-x-mark"
               class="h-5 w-5 text-red-500"
           /> {{ __('Failed') }}
        </span>

        @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_SCHEDULED)

            <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-green-200 rounded-full">
           <x-filament::icon
               icon="heroicon-m-clock"
               class="h-5 w-5 text-gray-800"
           /> {{ __('Scheduled') }}
        </span>
            <div>
                <span class="leading-1 text-xs text-gray-600 dark:text-gray-400">
                    {{ $currentRecord->scheduled_at }}
                    <br />
                    {{ $currentRecord->scheduled_timezone }}
                </span>
            </div>


    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_CANCELED)

        <span class="px-2 py-1 text-xs font-semibold leading-5 text-red-500 bg-red-200 rounded-full">
            {{ __('Canceled') }}
        </span>

    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_PENDING)

        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">
           <x-filament::loading-indicator class="h-5 w-5" /> {{ __('Pending') }}
        </span>

    @elseif($currentRecord->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_QUEUED)

        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">
       <x-filament::loading-indicator class="h-5 w-5" /> {{ __('Queued') }}
    </span>

    @else

        <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">

            {{ ucfirst($currentRecord->status) }}
        </span>

    @endif

</div>
