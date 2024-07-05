<div>


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

    <x-filament-actions::modals/>
</div>
