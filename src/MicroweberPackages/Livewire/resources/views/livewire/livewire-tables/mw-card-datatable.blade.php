<x-livewire-tables::wrapper :component="$this">
    <x-livewire-tables::tools>
        <x-livewire-tables::tools.sorting-pills />
        <x-livewire-tables::tools.filter-pills />

        @include('livewire::livewire.livewire-tables.components.tools.toolbar')

    </x-livewire-tables::tools>

    <div class="muted-cards">
    @forelse ($rows as $rowIndex => $row)
            <div class="card card-product-holder mb-2">
                <div class="card-body">
                    <div class="row align-items-center">
                   @foreach($columns as $colIndex => $column)

                       @dump($column->getComponent()->getTdAttributes($column, $row, $colIndex, $rowIndex))

                            {{ $column->renderContents($row) }}
                  @endforeach
                    </div>
                </div>
            </div>
    @empty
       no data
    @endforelse
    </div>

    <x-livewire-tables::pagination :rows="$rows" />

    @isset($customView)
        @include($customView)
    @endisset
</x-livewire-tables::wrapper>
