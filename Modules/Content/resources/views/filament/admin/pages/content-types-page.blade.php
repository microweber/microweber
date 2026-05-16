{{--
    task-2026-05-16-299f78 / AI-732 Phase 1 — Content Types
    listing page. Read-only aggregate of distinct content_type
    values with row counts. CTA + create-form deferred to Phase 2
    (AI-732-followup).
--}}
<x-filament-panels::page>
    @if (empty($contentTypes))
        {{-- Empty state — no content rows at all, or all soft-deleted.
             Designer dispatch says "empty state" was Phase 1 AC.
             Matches the AI-728/AI-729 empty-state shape (heading +
             explainer + permanent guidance). No CTA per Phase 1
             scope — Phase 2 adds the "+ Add content type" CTA. --}}
        <div class="mw-admin-empty-state-content-types text-center py-12">
            <h2 class="mw-admin-empty-state-heading text-center mt-4" style="font-weight: 600;">
                No content types yet
            </h2>
            <p class="mw-admin-empty-state-explainer text-center">
                Content types appear here once you create your first piece of content. Microweber's built-in types are <code>page</code>, <code>post</code>, and <code>product</code>; custom types extend that list.
            </p>
        </div>
    @else
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content-ctn p-6">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-white/10">
                            <th class="pb-3 font-semibold text-gray-950 dark:text-white">Content type</th>
                            <th class="pb-3 font-semibold text-gray-950 dark:text-white text-right">Rows</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contentTypes as $row)
                            <tr class="border-b border-gray-100 last:border-b-0 dark:border-white/5">
                                <td class="py-3 font-mono text-gray-700 dark:text-gray-200">
                                    <code>{{ $row['type'] }}</code>
                                </td>
                                <td class="py-3 text-right text-gray-700 dark:text-gray-200 tabular-nums">
                                    {{ number_format($row['count']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                    Phase 1 of AI-732 ships read-only listing. Per-type CRUD and field-schema editing arrive in Phase 2 and Phase 3.
                </p>
            </div>
        </div>
    @endif
</x-filament-panels::page>
