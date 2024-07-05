<div>
    <style>
        .list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .item {
            margin: 5px 0;
            padding: 10px;
            background-color: #f0f0f0;
            cursor: move;
        }
        .item ul {
            padding-left: 20px;
        }
    </style>



    <div x-data="sortableData()" x-init="init()">
        <ul id="parent-list" class="list">
            <li class="item" data-id="1">Item 1
                <ul class="list">
                    <li class="item" data-id="1-1">Subitem 1-1</li>
                    <li class="item" data-id="1-2">Subitem 1-2</li>
                </ul>
            </li>
            <li class="item" data-id="2">Item 2
                <ul class="list">
                    <li class="item" data-id="2-1">Subitem 2-1</li>
                </ul>
            </li>
            <li class="item" data-id="3">Item 3</li>
        </ul>
    </div>

    <script>
        function sortableData() {
            return {
                init() {
                    const parentList = document.getElementById('parent-list');
                    new Sortable(parentList, {
                        group: 'nested',
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        onEnd: this.updateOrder
                    });

                    // Initialize nested lists
                    const nestedLists = parentList.querySelectorAll('.list');
                    nestedLists.forEach((list) => {
                        new Sortable(list, {
                            group: 'nested',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onEnd: this.updateOrder
                        });
                    });
                },
                updateOrder(event) {
                    console.log('Item moved:', event.item);
                    console.log('New index:', event.newIndex);
                    // Implement logic to handle updated order here
                }
            }
        }
    </script>







    aaaaaaaaaaaaaaa

    <div class="admin-thumbs-holder" x-sortable>


        @foreach ($menus as $menu)



            <div
                    x-sortable-handle
                    x-sortable-item="{{ $menu->id }}"

            >





                <h2> {{ $menu->id }}  {{ $menu->title }}   {{ $menu->item_type }}</h2>


                {{ ($this->editAction)(['id' => $menu->id]) }}


                {{ ($this->deleteAction)(['id' => $menu->id]) }}

            </div>
        @endforeach

    </div>


    aaaaaa









    <x-filament-actions::modals/>
</div>
