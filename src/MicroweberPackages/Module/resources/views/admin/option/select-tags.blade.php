<div
    x-data="{openTags:false}"
    x-on:click.away="openTags = false"
>

    <style>
        .tags-list .tag .btn-close {
            color:#fff;
        }
        .tags-list .tag {
            background:#000;
            color:#fff;
            margin-right:5px;
        }

    </style>

    <div class="tags-list"  @if (empty($selectedTags)) style="display:none" @endif>
        @foreach($selectedTags as $selectedTag)
          <span class="tag">
            {{$selectedTag}}
            <a href="#" wire:click="removeTag('{{$selectedTag}}')" class="btn-close">X</a>
          </span>
        @endforeach
    </div>

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
