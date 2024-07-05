<div>
    @foreach ($menus as $menu)
        <h2>{{ $menu->name }}</h2>



    {{--    {{ ($this->editMenuByIdAction($menu->id)) }}--}}

        {{ ($this->editAction)(['id' => $menu->id]) }}


        {{ ($this->deleteAction)(['id' => $menu->id]) }}
    @endforeach

    <x-filament-actions::modals/>
</div>
