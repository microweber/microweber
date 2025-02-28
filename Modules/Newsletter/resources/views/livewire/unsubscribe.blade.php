<div class="flex flex-col justify-center items-center text-center">

    <form class="text-left w-[50rem] mt-8">

        <div class="bg-gray-50 sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Unsubscribe from our newsletter
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>
                        You are about to unsubscribe from our newsletter. Are you sure you want to do this?
                    </p>
                </div>
                <div class="mt-5">

                    <a href="{{site_url()}}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Cancel
                    </a>

                    <button wire:click="unsubscribe" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm font-medium rounded-md text-white bg-gray-900 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 sm:text-sm">
                        <div wire:target="unsubscribe" wire:loading.remove>
                            Unsubscribe
                        </div>
                        <div wire:target="unsubscribe" wire:loading>
                        Unsubscribing...
                        </div>
                    </button>

                </div>
            </div>
        </div>

    </form>

</div>
