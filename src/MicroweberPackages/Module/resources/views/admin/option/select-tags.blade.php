<div
    x-data="{openTags:false}"
    x-on:click.away="openTags = false"
>

    <div class="tags-list my-3 ms-2"  @if (empty($selectedTags)) style="display:none" @endif>
        @foreach($selectedTags as $selectedTag)
          <span class="tag">
            {{$selectedTag}}
            <a href="#" wire:click="removeTag('{{$selectedTag}}')" class="btn-close"></a>
          </span>
        @endforeach
    </div>

    <div>
        <x-microweber-ui::input wire:model="search" placeholder="Search tags..."  x-on:click="openTags = !openTags" />
    </div>

    <div x-show="openTags" class="form-control-live-edit-label-wrapper">

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
