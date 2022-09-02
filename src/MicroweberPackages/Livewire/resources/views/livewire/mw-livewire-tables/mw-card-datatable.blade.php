<x-livewire-tables::wrapper :component="$this">
    <x-livewire-tables::tools>
        <x-livewire-tables::tools.sorting-pills />
        <x-livewire-tables::tools.filter-pills />

        @include('livewire::livewire.mw-livewire-tables.components.tools.toolbar')

    </x-livewire-tables::tools>

    <div class="muted-cards">
    @forelse ($rows as $rowIndex => $row)

            <div class="card card-product-holder mb-2">
                <div class="card-body">
                    <div class="row align-items-center">

                 {{-- <div wire:sortable.handle>
                            sort
                        </div>
                    --}}

                   @foreach($columns as $colIndex => $column)

                       @php
                           $columnTdClass = '';
                           $tdAttributes = $column->getComponent()->getTdAttributes($column, $row, $colIndex, $rowIndex);
                           if (isset($tdAttributes['class'])) {
                               $columnTdClass = $tdAttributes['class'];
                           }
                       @endphp

                        <div class="{{$columnTdClass}}">
                        {{ $column->renderContents($row) }}
                    </div>
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
