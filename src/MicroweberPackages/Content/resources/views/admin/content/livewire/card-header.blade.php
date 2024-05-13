<div class="card-header">
    <div class="col d-flex justify-content-start align-items-center">
        <div class="d-flex align-items-center">

            <h1 class="main-pages-title d-lg-flex card-title">

                <a class="@if(isset($currentCategory) and $currentCategory) cursor-pointer text-decoration-none @else cursor-pointer text-decoration-none @endif" onclick="livewire.emit('deselectAllCategories');return false;">


                    @if($this->contentType == 'content')
                        {{_e('Content')}}
                    @elseif($this->contentType == 'post')
                        {{_e('Posts')}}
                    @elseif($this->contentType == 'product')
                        {{_e('Products')}}
                    @elseif($this->contentType == 'category')
                        {{_e('Categories')}}
                    @elseif($this->contentType == 'page')
                        {{_e('Pages')}}
                    @else
                        {{_e(ucfirst($this->contentType))}}
                    @endif

               </a>


            @if(isset($currentPage) and $currentPage)
                <small class="text-muted"> &nbsp; \ &nbsp; </small>
                <a class="cursor-pointer form-label text-muted" onclick="livewire.emit('selectPageFromTableList','{{$currentPage['id']}}');return false;">
                    {{$currentPage['title']}}
                </a>
            @endif


            @if(isset($currentCategory) and $currentCategory)
                <small class="text-muted"> &nbsp; \ &nbsp; </small>

                <a class="cursor-pointer form-label text-muted" onclick="livewire.emit('selectCategoryFromTableList','{{$currentCategory['id']}}');return false;">
                {{$currentCategory['title']}}
                </a>
            @endif



            @if($isInTrashed)
                <span class="form-label text-muted">&nbsp; \ &nbsp;
              {{ _e('Trash') }}
                </span>
            @endif

{{--                @if(isset($currentCategory) and $currentCategory)--}}
{{--                    <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">--}}
{{--                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg>--}}
{{--                    </a>--}}
{{--                @endif--}}
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

