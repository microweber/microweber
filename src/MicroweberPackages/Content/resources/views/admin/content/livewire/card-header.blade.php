<div class="card-header">
    <div class="col d-flex justify-content-start align-items-center">
        <div class="d-flex align-items-center">

            <h1 class="main-pages-title d-md-flex d-none card-title">

                <a class="@if(isset($currentCategory) and $currentCategory) text-decoration-none @else text-decoration-none   ms-1 @endif" onclick="livewire.emit('deselectAllCategories');return false;">
                    {{_e(ucfirst($this->contentType).'s')}}
                </a>

                @if(isset($currentCategory) and $currentCategory)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    {{$currentCategory['title']}}
                @endif

                @if($isInTrashed)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    <i class="mdi mdi-trash-can"></i> {{ _e('Trash') }}
                @endif

                @if(isset($currentCategory) and $currentCategory)
                    <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg>
                    </a>
                @endif
            </h1>

        </div>
        <div>

{{--            @if(isset($contentType))--}}
{{--                @include('content::admin.content.livewire.create-content-buttons',['buttonClass'=>'btn btn-outline-primary btn-sm'])--}}

{{--            @endif--}}




            <?php
            /*  @if(isset($currentCategory) and $currentCategory)
            <a href="{{category_link($currentCategory['id'])}}" target="_blank" title="{{_e('View category')}}"><span class="fa fa-external-link-alt"></span></a>
            @endif*/

            ?>



        </div>
    </div>
</div>

