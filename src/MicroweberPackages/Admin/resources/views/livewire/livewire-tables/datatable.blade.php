<x-livewire-tables::wrapper :component="$this">
    <x-livewire-tables::tools>
        <x-livewire-tables::tools.sorting-pills />
        <x-livewire-tables::tools.filter-pills />
        <x-livewire-tables::tools.toolbar />
    </x-livewire-tables::tools>

    @forelse ($rows as $rowIndex => $row)

        @foreach($columns as $colIndex => $column)
            {{ $column->renderContents($row) }}
        @endforeach

    @empty
       no data
    @endforelse

    <x-livewire-tables::pagination :rows="$rows" />

    @isset($customView)
        @include($customView)
    @endisset
</x-livewire-tables::wrapper>
