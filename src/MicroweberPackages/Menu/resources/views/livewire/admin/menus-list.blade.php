<div>
    <div wire:ignore>

        <script>
            function sortableData() {
                return {
                    init() {

                        //onclick on .menu_element alert
                        var menuElements = document.querySelectorAll('.admin-menu-items-holder .menu_element_link');
                        for (var i = 0; i < menuElements.length; i++) {
                            if (menuElements[i].classList.contains('binded-click')) {
                                continue;
                            }
                            menuElements[i].classList.add('binded-click');


                            menuElements[i].addEventListener('click', (e)  =>{
                                e.stopPropagation();
                                e.preventDefault();

                                var id = e.target.getAttribute('data-item-id');
                              //  Livewire.dispatch('editMenuItem',{id: id});

                                this.$wire.mountAction('editAction', { id: id })


                                //s  alert(id)
                            });
                        }

                    },
                    updateOrder(event) {

                    }
                }
            }


        </script>
    </div>

    <div x-data="sortableData()" x-init="init()">

    </div>


    <div class="admin-menu-items-holder">


        @foreach ($menus as $menu)

            <div>


                @php
                    $params = array(
                      'menu_id' => $menu->id,

                  );
                     print  menu_tree($params);
                @endphp


                <h2> {{ $menu->id }}  {{ $menu->title }}   {{ $menu->item_type }}</h2>


                {{ ($this->editAction)(['id' => $menu->id]) }}


                {{ ($this->deleteAction)(['id' => $menu->id]) }}

            </div>
        @endforeach

    </div>


    aaaaaa


    <x-filament-actions::modals/>
</div>
