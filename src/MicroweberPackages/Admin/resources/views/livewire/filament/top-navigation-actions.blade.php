{{-- AI-704 / task-2026-05-16-225150 — +Add re-clustered with Live Edit
     on the right side of admin top bar (was a standalone TOPBAR_START
     hook isolated at the left). The button is now icon-only (24-32 px
     hit, 16 px Plus icon) to match the MwToolButton default variant
     from spec §2 AD3; the visible "Add" label is hidden via .sr-only
     so AT users still hear it, and an explicit aria-label is set on
     the trigger so screen readers don't fall back to icon-only output.
     Geometry + ghost background live in general-styles.css. --}}
<div class="flex justify-between">
    <x-filament::modal width="lg">
        <x-slot name="trigger">
            <x-filament::button
                icon="heroicon-m-plus"
                class="admin-toolbar-buttons admin-toolbar-add"
                aria-label="Add new content"
                title="Add new content"
            >
                <span class="sr-only">Add</span>
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Add new
        </x-slot>

        <x-slot name="description">
            {{--
                task-2026-05-05-5e9ffc (Audit-#1) — drunk-designer
                external audit found this modal lacked proper
                affordances. Added aria-label to each <a> card so
                screen-reader users hear the full title + description
                rather than just the inner text. Real <a href> is
                already used (correct semantic for navigation), and
                Filament's <x-filament::modal> component handles
                role="dialog" on the wrapping window. Adding
                tabindex+focus rings via Tailwind so keyboard users
                land on each card visibly.
            --}}
            <div class="mb-6 p-4">
               @foreach($links as $link)

                    <a href="{{ $link['url'] }}"
                       aria-label="{{ $link['title'] }}: {{ $link['description'] }}"
                       class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 rounded-md">
                        <div class="flex gap-6 p-4 group hover:scale-105 transition duration-150 hover:bg-blue-500/10 dark:hover:bg-blue-500/15 rounded-md w-full">
                            <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 dark:bg-blue-500/10 transition duration-150 group-hover:bg-white dark:group-hover:bg-gray-700 shadow-md rounded p-4">
                                @svg($link['icon'], "h-10 w-10 text-black/80 dark:text-gray-200")
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                               <div class="font-bold dark:text-gray-100">
                                   {{ $link['title'] }}
                                 </div>
                                <div class="text-sm dark:text-gray-400">
                                    {{ $link['description'] }}
                                </div>
                            </div>
                        </div>
                    </a>

               @endforeach
            </div>
        </x-slot>

    </x-filament::modal>

</div>
