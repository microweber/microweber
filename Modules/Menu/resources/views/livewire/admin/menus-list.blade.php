<div>

    @script
    <script>
        document.addEventListener('livewire:initialized', async () => {
        })
    </script>
    @endscript

    <div class="mw-menu-editor">

        {{-- Menu selector bar.
             items-end aligns the 3-dot ActionGroup to the bottom edge
             of the Filament Select (which has its own label above the
             input). justify-start (replacing justify-between) puts the
             3-dot button right next to the dropdown instead of
             floating off to the far-right empty space — see
             task-2026-04-30-7e6b01. --}}
        <div class="mw-menu-editor__selector">
            <div class="flex gap-2 items-end justify-start flex-wrap">
                <div class="flex-1 min-w-[200px] max-w-sm">
                    {{ $this->form }}
                </div>
                <div class="flex gap-2 items-center" style="padding-bottom: 2px;">
                    {{ $this->menuActionsGroup() }}
                </div>
            </div>
        </div>

        @if($menu)

            {{-- Toolbar --}}
            <div class="mw-menu-editor__toolbar">
                <div class="flex gap-2 items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                            {{ $menu->title }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Drag items to reorder. Nest items by dragging them under a parent.
                        </p>
                    </div>
                    <div>
                        {{ ($this->addMenuItemAction)(['parent_id' => $menu->id]) }}
                    </div>
                </div>
            </div>

            {{-- Menu tree --}}
            <div
                x-load="visible"
                x-load-src="{{ asset('modules/menu/js/sortableMenu.js') }}"
                x-data="sortableMenu()"
            >
                <div class="mw-menu-editor__tree" data-menu-id="{{ $menu->id }}">
                    @php
                        // Capture $this (the MenusList Livewire
                        // component) before defining the closure —
                        // PHP closures inside Blade-compiled @php
                        // blocks do NOT auto-bind $this from the
                        // outer view's compile scope. Without this,
                        // calls to $this->editAction inside the
                        // child blade throw "Using $this when not
                        // in object context" and silently destroy
                        // the rendered menu item rows
                        // (task-2026-04-29-dcde6d).
                        $component = $this;
                        $params = array(
                          'menu_id' => $menu->id,
                          'link' => function ($item) use ($component) {
                              return view('modules.menu::livewire.admin.menu-list-item', [
                                  'item' => $item,
                                  'component' => $component,
                              ])->render();
                          }
                         );
                         $menuTree = menu_tree($params);
                    @endphp

                    @if($menuTree)
                        {!! $menuTree !!}
                    @else
                        <div class="mw-menu-editor__empty">
                            <div class="flex flex-col justify-center items-center py-12">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-medium text-gray-600 dark:text-gray-300">
                                    No menu items yet
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">
                                    Add links to pages, categories, or custom URLs
                                </p>
                                {{ ($this->addMenuItemAction)(['parent_id' => $menu->id]) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @else
            {{-- No menus exist --}}
            <div class="mw-menu-editor__empty mt-6">
                <div class="flex flex-col justify-center items-center py-12">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </div>
                    <h3 class="text-base font-medium text-gray-600 dark:text-gray-300">
                        No menus found
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">
                        Create your first menu to get started
                    </p>
                    {{ $this->createAction }}
                </div>
            </div>
        @endif

    </div>

    {{-- task-2026-06-05-mnu-modal — Restore Filament modal overlay chrome for
         the Add menu item / Edit create dialog rendered below. On this admin
         settings page (both standalone at /admin/menu-module-settings and
         inside the live-edit Module Settings iframe) a runtime probe found the
         create modal opened with no backdrop dim (the close overlay computed to
         a fully transparent background) and with the window container aligned to
         the top of the viewport instead of the centre, so the dialog read as a
         bare inline box overlapping the menu list rather than a proper centred
         modal. Restore vertical centring + a dimmed backdrop. Scoped to
         non-slide-over modals so the live-edit slide-over panel keeps its own
         right-edge alignment. Same per-surface backdrop patch pattern as the
         live-edit content-picker modal in live-edit-classes.css. --}}
    <style>
        body.fi-panel-admin .fi-modal:not(.fi-modal-slide-over) .fi-modal-window-ctn {
            align-items: center;
        }
        body.fi-panel-admin .fi-modal:not(.fi-modal-slide-over) .fi-modal-close-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>

    <x-filament-actions::modals/>
</div>
