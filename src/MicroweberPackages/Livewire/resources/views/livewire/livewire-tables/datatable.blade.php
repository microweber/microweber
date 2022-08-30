<x-livewire-tables::wrapper :component="$this">
    <x-livewire-tables::tools>
        <x-livewire-tables::tools.sorting-pills />
        <x-livewire-tables::tools.filter-pills />

        @include('livewire::livewire.livewire-tables.components.tools.toolbar')

    </x-livewire-tables::tools>

    <div class="muted-cards">
    @forelse ($rows as $rowIndex => $row)
        @foreach($columns as $colIndex => $column)
            {{ $column->renderContents($row) }}
        @endforeach
    @empty
       no data
    @endforelse
    </div>

    <x-livewire-tables::pagination :rows="$rows" />

    @isset($customView)
        @include($customView)
    @endisset
</x-livewire-tables::wrapper>
