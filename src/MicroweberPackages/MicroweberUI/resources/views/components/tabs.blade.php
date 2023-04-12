@props(['tabs'])

@php
$tabs = explode(',', $tabs);
@endphp

<div>
    <div class="mb-2">
        <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">

            @if(isset($tabs) && is_array($tabs))
                @foreach($tabs as $iTabItem=>$tabItem)
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-{{md5($tabItem . $iTabItem)}}" class="nav-link @if ($iTabItem == 0) active @endif" data-bs-toggle="tab" aria-selected="true" role="tab">
                            {{$tabItem}}
                        </a>
                    </li>
                @endforeach
            @endif

        </ul>
    </div>

    <div class="tab-content">

        @if(isset($tabs) && is_array($tabs))
            @foreach($tabs as $iTabItem=>$tabItem)
        <div class="tab-pane @if ($iTabItem == 0) active show @endif" id="tabs-{{md5($tabItem . $iTabItem)}}" role="tabpanel">
            <h4>{{$tabItem}}</h4>
            <div>

            </div>
        </div>
            @endforeach
        @endif

    </div>
</div>
