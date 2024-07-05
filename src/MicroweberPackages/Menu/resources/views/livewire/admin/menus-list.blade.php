<div>
    @foreach ($menus as $menu)
        <h2> {{ $menu->id }}  {{ $menu->title }}   {{ $menu->item_type }}</h2>




        {{ ($this->editAction)(['id' => $menu->id]) }}


        {{ ($this->deleteAction)(['id' => $menu->id]) }}
    @endforeach

    <x-filament-actions::modals/>
</div>
