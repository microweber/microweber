
@php

    $selectedOption = '';
    if (isset($this->state['settings']) and isset($this->state['settings'][$this->optionKey])) {
        $selectedOption = $this->state['settings'][$this->optionKey];
    }

    $selectedPageTree = [];
    if (isset($pagesTree[$selectedOption])) {
        $selectedPageTree = [
            'id' => $selectedOption,
            'name' => $pagesTree[$selectedOption]
        ];
    }
    $selectedPageTree = json_encode($selectedPageTree);

@endphp

<div class="form-control-live-edit-label-wrapper" x-data="{selectedPage: {{$selectedPageTree}}, openOptions:false}">

    <button type="button" class="form-select form-control-live-edit-input"
            x-on:click="openOptions = !openOptions" x-html="selectedPage.name">
    </button>

    <div class="dropdown-menu form-control-live-edit-input ps-0" style="max-height:300px;overflow-y: scroll" :class="[openOptions ? 'show':'']">

        <div>
            <x-microweber-ui::input wire:model="search" placeholder="Search here..." />
        </div>

        @if(!empty($pagesTree))
            @foreach($pagesTree as $pageId=>$pageName)
                <button type="button"
                        wire:click="selectPage({{$pageId}})"
                        x-on:click="selectedPage = {{json_encode(['id'=>$pageId, 'name'=>$pageName])}}; openOptions = false" :class="[selectedPage.id == '{{$pageId}}' ? 'active':'']" class="dropdown-item tblr-body-color">
                    {!! $pageName !!}
                    <span class="ms-auto" x-show="selectedPage.id == '{{$pageId}}'">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>
                    </span>
                </button>
            @endforeach
        @endif

    </div>

</div>
