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

        <script>
            function sortableData() {
                return {
                    init() {

                        var _orderChangeHandle = function (e, ui){
                            setTimeout(function(){
                                var old = $.extend({},ui.item[0]._data);
                                var obj = ui.item[0]._data;
                                var objParent = ui.item[0].parentNode.parentNode._data;
                                ui.item[0].dataset.parent_id = objParent ? objParent.id : 0;

                                obj.parent_id = objParent ? objParent.id : 0;
                                obj.parent_type = objParent ? objParent.id : 'page';
                                var newdata = [];
                                mw.$('li', scope.list).each(function(){
                                    if(this._data) newdata.push(this._data);
                                });
                                scope.options.data = newdata;
                                var local = [];
                                mw.$(ui.item[0].parentNode).children('li').each(function(){
                                    if(this._data) {
                                        local.push(this._data.id);
                                    }
                                });
                                //$(scope.list).remove();
                                //scope.init();
                                mw.$(scope).trigger('orderChange', [obj, scope.options.data, old, local]);
                                scope.dispatch('orderChange', [obj, scope.options.data, old, local]);
                            }, 110);
                        };

                        console.log(1111111111111111111)
                        console.log($('ul','.admin-menu-items-holder'))


                        var sortableLists = $('ul','.admin-menu-items-holder');

                        for (var i = 0; i < sortableLists.length; i++) {

                            $(sortableLists[i]).nestedSortable({
                                items: ".menu_element",
                                listType:'ul',
                                handle:'.menu_element',
                                start: function(){ // firefox triggers click when drag ends
                                    // scope._disableClick = true;
                                },
                                stop: function(){
                                    //  setTimeout(() => {scope._disableClick = false;}, 78)
                                },
                                update:function(e, ui){
                                    _orderChangeHandle(e, ui);
                                }
                            });


                        }



                        //
                        // //onclick on .menu_element alert
                        // var menuElements = document.querySelectorAll('.admin-menu-items-holder .menu_element_link');
                        // for (var i = 0; i < menuElements.length; i++) {
                        //     if (menuElements[i].classList.contains('binded-click')) {
                        //         continue;
                        //     }
                        //     menuElements[i].classList.add('binded-click');
                        //
                        //
                        //     menuElements[i].addEventListener('click', (e) => {
                        //         e.stopPropagation();
                        //         e.preventDefault();
                        //
                        //         var id = e.target.getAttribute('data-item-id');
                        //         //  Livewire.dispatch('editMenuItem',{id: id});
                        //
                        //         this.$wire.mountAction('editAction', {id: id})
                        //
                        //
                        //         //s  alert(id)
                        //     });
                        //
                        // }

                    },
                    updateOrder(event) {

                    }
                }
            }


        </script>
    </div>

    <div x-data="sortableData()" x-init="init()">


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


    </div>


    <x-filament-actions::modals/>
</div>
