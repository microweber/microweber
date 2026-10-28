{{-- Connection config for an external MCP agent. Rendered inside the
     "Connect" action modal on McpClientResource. The blocks come from the
     shared McpConnectionConfig service so they match `ai:mcp:connect`. --}}
<div class="space-y-4 text-sm">
    <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5">
        <div class="font-medium text-gray-950 dark:text-white">HTTP endpoint (Streamable HTTP)</div>
        <code class="break-all text-primary-600 dark:text-primary-400">{{ $endpoint }}</code>
        <div class="mt-2 font-medium text-gray-950 dark:text-white">Transport over stdio</div>
        <code class="break-all text-gray-700 dark:text-gray-300">{{ $stdioCommand }}</code>
    </div>

    @unless ($hasToken)
        <div class="rounded-lg border border-warning-300 bg-warning-50 p-3 text-warning-700 dark:border-warning-500/30 dark:bg-warning-500/10 dark:text-warning-400">
            Replace <code class="font-semibold">{{ $placeholder }}</code> with a key issued from the
            <strong>Keys</strong> tab (it is shown only once), or run
            <code class="font-semibold">php artisan ai:mcp:connect</code> to print a config with a fresh key already embedded.
        </div>
    @endunless

    @foreach ($blocks as $block)
        <div x-data="{ copied: false }">
            <div class="mb-1 flex items-center justify-between gap-2">
                <span class="font-semibold text-gray-950 dark:text-white">{{ $block['title'] }}</span>
                <button
                    type="button"
                    x-on:click="navigator.clipboard.writeText($refs.code{{ $loop->index }}.innerText); copied = true; setTimeout(() => copied = false, 1500)"
                    class="shrink-0 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20"
                >
                    <span x-show="!copied">Copy</span>
                    <span x-show="copied" x-cloak>Copied</span>
                </button>
            </div>
            @if ($block['hint'])
                <div class="mb-1 text-xs text-gray-500 dark:text-gray-400">{{ $block['hint'] }}</div>
            @endif
            <pre x-ref="code{{ $loop->index }}" class="overflow-x-auto rounded-lg bg-gray-950 p-3 text-xs leading-relaxed text-gray-100 dark:bg-black/40"><code>{{ $block['body'] }}</code></pre>
        </div>
    @endforeach
</div>
