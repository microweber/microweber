<div>

    @script
    <script>

        document.addEventListener('livewire:initialized', async () => {


        })
    </script>
    @endscript

    <div>

        <style>
            .admin-menu-items-holder ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }

            .admin-menu-items-holder ul ul {
                margin-left: 20px;
            }
        </style>


    </div>


    <div class="flex gap-3 justify-between">
        <h4 class="text-xl">
            Select the Menu you want to edit
        </h4>
        {{ ($this->createAction) }}
    </div>

    @if($menu)
    {{ $this->form }}
    @endif

    @if($menu)

        <div class="mt-4">
            {{ ($this->addMenuItemAction)(['parent_id' => $menu->id]) }}
        </div>



        <hr class="mt-6" />

        <div class="mt-8">
            <div class="text-sm uppercase font-bold">
                {{ $menu->title }}  structure
            </div>
           <p class="text-sm">
               Here you can edit your menu links. You can also drag and drop to reorder them.
           </p>
        </div>
        <div
            x-ignore
            ax-load
            ax-load-src="{{ asset('modules/menu/js/sortableMenu.js') }}"
            x-data="sortableMenu()"
        >
                <div class="admin-menu-items-holder bg-white shadow mt-4 mb-4">
                    <div data-menu-id="{{ $menu->id }}" class="px-4 pb-4 pt-4">
                        @php
                            $params = array(
                              'menu_id' => $menu->id,
                              'link' => function ($item) {
                                  return view('modules.menu::livewire.admin.menu-list-item', ['item'=>$item])->render();
                              }
                             );
                             $menuTree = menu_tree($params);

                             echo $menuTree;
                        @endphp

                        @if(!$menuTree)

                            <div class="flex flex-col justify-center items-center py-8">
                                <h3 class="text-center text-xl text-gray-500">
                                    No menu items found
                                </h3>

                                <p class="mt-2">
                                    Create your first menu item
                                </p>

                                <div class="mt-4">
                                    {{ ($this->addMenuItemAction)(['parent_id' => $menu->id]) }}
                                </div>

                            </div>

                        @endif

                    </div>
                </div>
            </div>

        <div class="flex gap-2 items-center justify-between mt-4">
            <div class="text-sm uppercase font-bold">
                {{ $menu->title }}  selected
            </div>

            <div>
                {{ ($this->editAction)(['id' => $menu->id]) }}
                {{ ($this->deleteAction)(['id' => $menu->id]) }}
            </div>
        </div>

    @endif


    <x-filament-actions::modals/>
</div>
