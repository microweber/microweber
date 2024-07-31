<div>

    @script
    <script>
        mw.lib.require('jqueryui')
        mw.lib.require('nestedSortable')
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

        <script>
            function sortableMenu() {
                return {
                    async init() {


                        const collectTreeElements = target => {
                            if (!target) {
                                console.log('target is not defined')
                                return [];
                            }
                            var closestMenuId = 0;
                            var closestMenuIdElement = target.closest('[data-menu-id]');
                            if (closestMenuIdElement) {
                                closestMenuId = closestMenuIdElement.getAttribute('data-menu-id');
                            }


                            return Array.from(closestMenuIdElement.querySelectorAll('li')).map(node => {
                                const parent = node.parentNode.closest('li');
                                return {
                                    id: node.dataset.itemId,
                                    parentId: parent ? parent.dataset.itemId : closestMenuId
                                }
                            });
                        }

                        var _orderChangeHandleTimeout = null;

                        var _orderChangeHandle = function (e, ui) {
                            clearTimeout(_orderChangeHandleTimeout);
                            _orderChangeHandleTimeout = setTimeout(function () {
                                var result = collectTreeElements(e.target);
                                result = {'items': result};



                                $.post("<?php echo route('api.menu.item.reorder'); ?>", result, function () {
                                    if (mw.notification) {
                                        mw.notification.success('<?php _ejs("Menu changes are saved"); ?>');
                                    }
                                });


                            }, 100);
                        };


                        var sortableLists = $('ul', '.admin-menu-items-holder');

                        for (var i = 0; i < sortableLists.length; i++) {

                            $(sortableLists[i]).nestedSortable({
                                items: ".menu_element",
                                listType: 'ul',
                                //handle:'.menu_element',
                                start: function () { // firefox triggers click when drag ends
                                    // scope._disableClick = true;
                                },
                                stop: function () {
                                    //  setTimeout(() => {scope._disableClick = false;}, 78)
                                },
                                update: function (e, ui) {
                                    _orderChangeHandle(e, ui);
                                }
                            });


                        }


                        // //onclick on .menu_element
                        var menuElements = document.querySelectorAll('.admin-menu-items-holder .menu_element_link');
                        for (var i = 0; i < menuElements.length; i++) {
                            if (menuElements[i].classList.contains('binded-click')) {
                                continue;
                            }
                            menuElements[i].classList.add('binded-click');


                            menuElements[i].addEventListener('click', (e) => {
                                e.stopPropagation();
                                e.preventDefault();

                                var id = e.target.getAttribute('data-item-id');

                                this.$wire.mountAction('editAction', {id: id})


                            });

                        }

                    },
                    updateOrder(event) {

                    }
                }
            }


        </script>
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

        <div x-data="sortableMenu()" x-init="init()">
                <div class="admin-menu-items-holder bg-white shadow mt-4 mb-4">
                    <div data-menu-id="{{ $menu->id }}" class="px-4 pb-4 pt-4">
                        @php
                            $params = array(
                              'menu_id' => $menu->id,
                              'link' => function ($item) {
                                  return view('menu::livewire.admin.menu-list-item', ['item'=>$item])->render();
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
