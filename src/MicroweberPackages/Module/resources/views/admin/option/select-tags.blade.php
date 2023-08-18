
@php
    $selectedOption = '';
    if (isset($this->state['settings']) and isset($this->state['settings'][$this->optionKey])) {
        $selectedOption = $this->state['settings'][$this->optionKey];
    }
@endphp

<div x-data="{openTags:false}">

    @if (!empty($selectedTags))
    <div class="tags-list">
        @foreach($selectedTags as $selectedTag)
          <span class="tag">
            {{$selectedTag}}
            <a href="#" wire:click="removeTag('{{$selectedTag}}')" class="btn-close"></a>
          </span>
        @endforeach
    </div>
    @endif

    <div>
        <x-microweber-ui::input wire:model="search" placeholder="Search tags..."  x-on:click="openTags = !openTags" />
    </div>

    <div class="form-control-live-edit-label-wrapper">

        <div class="dropdown-menu form-control-live-edit-input ps-0" style="max-height:300px;overflow-y: scroll" :class="[openTags ? 'show':'']">

            @if(!empty($tags))
                @foreach($tags as $tagId=>$tagName)
                    <button type="button"
                            wire:click="appendTag('{{$tagName}}')"
                            x-on:click="openTags = false"
                            class="dropdown-item tblr-body-color">
                        {!! $tagName !!}
                    </button>
                @endforeach
            @endif

        </div>

    </div>
</div>
