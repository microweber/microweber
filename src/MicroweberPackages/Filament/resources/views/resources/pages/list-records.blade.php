@php
    $reflect = new ReflectionClass($this->getResource());
    $navigationGroup = $reflect->getProperty('navigationGroup')->getValue();
    $navigationIcon = $reflect->getProperty('navigationIcon')->getValue();
@endphp
<div class="row">
    <div class="col-md-12">
        <div>
            <x-dynamic-component
                :component="$navigationIcon"

                @class([
                    'h-6 w-6 float-left mr-2 mt-1 shrink-0',
                ])

            />
            <h5>
                <strong class="text-xl">
                    {{$navigationGroup}}
                </strong>
            </h5>
        </div>

        <div class="card-body mb-3 mt-3">
            <div class=" ">
                <style>
                    .nav-tabs .nav-link {
                        margin:0px;
                    }
                </style>
                <ul class="nav nav-tabs">

                    @php
                        $navigationGroups = \Filament\Facades\Filament::getNavigation();
                    @endphp

                    @if(isset($navigationGroups[$navigationGroup]))
                        @foreach($navigationGroups[$navigationGroup]->getItems() as $navigationItem)
                            <li class="nav-item">
                                <a href="{{$navigationItem->getUrl()}}" class="nav-link @if($navigationItem->isActive()) active @endif">
                                    {{ $navigationItem->getLabel() }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                </ul>
                <div class="mt-2">

                    <x-filament::page
                        :class="\Illuminate\Support\Arr::toCssClasses([
                        'filament-resources-list-records-page',
                        'filament-resources-' . str_replace('/', '-', $this->getResource()::getSlug()),
                    ])"
                    >
                    {{ $this->table }}

                    </x-filament::page>

                </div>
            </div>
        </div>
    </div>
</div>

