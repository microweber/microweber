<x-filament-panels::page>
    {{-- task-2026-06-06-AI839 — mount() redirects to the signed-in user's edit
         page; this view only shows if the redirect has not yet fired. --}}
    <div class="mw-admin-empty-state" style="text-align:center;padding:2rem;">
        <p>{{ __('Opening your profile…') }}</p>
    </div>
</x-filament-panels::page>
