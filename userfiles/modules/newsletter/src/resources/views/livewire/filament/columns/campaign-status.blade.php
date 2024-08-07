@php
$record = $getRecord();
@endphp
<div>

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_DRAFT)
        <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">
            {{ __('Draft') }}
        </span>
    @endif

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_PROCESSING)
        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-yellow-200 rounded-full">
            <x-filament::loading-indicator class="h-5 w-5" /> {{ __('Processing') }}
        </span>
    @endif

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_FINISHED)
        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-green-200 rounded-full">
           <x-filament::icon
               icon="heroicon-m-check"
               class="h-5 w-5 text-gray-500 dark:text-gray-400"
           /> {{ __('Finished') }}
        </span>
    @endif

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_PAUSED)
        <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-blue-200 rounded-full">
            {{ __('Paused') }}
        </span>
    @endif

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_CANCELED)
        <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-red-200 rounded-full">
            {{ __('Canceled') }}
        </span>
    @endif

    @if($record->status === \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::STATUS_PENDING)
        <span class="flex gap-2 px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-200 rounded-full">
           <x-filament::loading-indicator class="h-5 w-5" /> {{ __('Pending') }}
        </span>
    @endif


</div>
