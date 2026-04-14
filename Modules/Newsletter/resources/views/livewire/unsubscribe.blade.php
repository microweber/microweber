<div class="mx-auto flex min-h-screen max-w-3xl flex-col justify-center px-4 py-10 text-center sm:px-6">
    <div class="rounded-xl bg-gray-50 text-left shadow-sm sm:rounded-2xl">
        <div class="px-6 py-8 sm:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">
                Unsubscribe from our newsletter
            </h1>

            @if ($pageState === \Modules\Newsletter\Livewire\UnsubscribePage::STATE_CONFIRM)
                <div class="mt-3 space-y-2 text-sm text-gray-600">
                    <p>You are about to unsubscribe <span class="font-medium text-gray-900">{{ $email }}</span> from future newsletter emails.</p>
                    @if ($subscriberName)
                        <p>Subscriber: <span class="font-medium text-gray-900">{{ $subscriberName }}</span></p>
                    @endif
                    <p>Are you sure you want to continue?</p>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ site_url() }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>

                    <button wire:click="unsubscribe" type="button" class="inline-flex items-center justify-center rounded-md border border-gray-900 bg-gray-900 px-4 py-2 font-medium text-white hover:bg-gray-800">
                        <span wire:target="unsubscribe" wire:loading.remove>Unsubscribe</span>
                        <span wire:target="unsubscribe" wire:loading>Unsubscribing...</span>
                    </button>
                </div>
            @elseif ($pageState === \Modules\Newsletter\Livewire\UnsubscribePage::STATE_SUCCESS)
                <div class="mt-3 space-y-2 text-sm text-gray-600">
                    <p>You have been unsubscribed successfully.</p>
                    <p><span class="font-medium text-gray-900">{{ $email }}</span> will no longer receive newsletter messages.</p>
                </div>

                <div class="mt-6">
                    <a href="{{ site_url() }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 hover:bg-gray-100">
                        Return to site
                    </a>
                </div>
            @elseif ($pageState === \Modules\Newsletter\Livewire\UnsubscribePage::STATE_ALREADY_UNSUBSCRIBED)
                <div class="mt-3 space-y-2 text-sm text-gray-600">
                    <p>This email address is already unsubscribed.</p>
                    <p><span class="font-medium text-gray-900">{{ $email }}</span> is not receiving newsletter messages.</p>
                </div>

                <div class="mt-6">
                    <a href="{{ site_url() }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 hover:bg-gray-100">
                        Return to site
                    </a>
                </div>
            @else
                <div class="mt-3 space-y-2 text-sm text-gray-600">
                    <p>This unsubscribe link is invalid or has expired.</p>
                    <p>Please use the latest unsubscribe link from one of our emails.</p>
                </div>

                <div class="mt-6">
                    <a href="{{ site_url() }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 hover:bg-gray-100">
                        Return to site
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
