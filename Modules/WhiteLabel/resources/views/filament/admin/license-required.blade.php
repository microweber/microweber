<div class="flex flex-col items-center justify-center gap-y-6 py-12">
    <div class="w-24 h-24 rounded-full bg-amber-100 flex items-center justify-center">
        <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
    
    <div class="text-center gap-y-2">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            White Label License Required
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md">
            To access White Label features and customize your branding, you need a valid White Label license.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <a href="https://microweber.com/pricing#white-label" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Purchase License
        </a>
        
        <button 
            type="button"
            x-on:click="$wire.mountAction('manage-licenses')"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            + Add License Key
        </button>
    </div>
    
    <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
        <p>Already have a license? Use the "Add License Key" button above to activate it.</p>
    </div>
</div>
