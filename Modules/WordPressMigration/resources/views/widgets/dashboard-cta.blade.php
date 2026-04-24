<x-filament-widgets::widget>
    <a
        href="{{ $this->getImportUrl() }}"
        class="block rounded-xl ring-1 ring-gray-950/5 bg-gradient-to-br from-[#21759b] to-[#1b5f7e] text-white shadow-sm transition hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
        data-testid="wp-import-cta"
    >
        <div class="flex flex-wrap items-center gap-6 p-6 sm:flex-nowrap">
            <div class="shrink-0 rounded-lg bg-white/10 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18.5a8.5 8.5 0 0 1-7.31-4.17L9.06 9.5l2.16 5.94-1.7 4.94A8.47 8.47 0 0 1 12 20.5Zm-5.52-12.9h2.63l3.06 8.37-1.17 3.38a8.5 8.5 0 0 1-4.52-11.75Zm12.33 3.68a8.46 8.46 0 0 1-1.14 7.4l-3.03-8.33h-.36a3.21 3.21 0 0 1 .63-.07c.57 0 1.11.09 1.6.25l-.02-.07h-3.17l2.53 7.52-1.43 4.29-2.5-7.16 1.14-.45a3.2 3.2 0 0 1 .1-.08Z"/>
                </svg>
            </div>

            <div class="min-w-0 grow">
                <div class="text-xs uppercase tracking-wide text-white/70">Start here</div>
                <h2 class="mt-1 text-lg font-semibold leading-snug">Migrating from WordPress?</h2>
                <p class="mt-1 text-sm text-white/85">
                    Import your posts, pages, tags, categories, and media from a WordPress site — by URL, RSS feed, sitemap, or a WXR export file.
                    Preview everything before it lands on live content.
                </p>
            </div>

            <div class="shrink-0 text-sm font-medium">
                <span class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2 hover:bg-white/25 transition">
                    Open importer
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 0 1 1-1h7.586l-2.293-2.293a1 1 0 1 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 1 1-1.414-1.414L13.586 11H6a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>
    </a>
</x-filament-widgets::widget>
