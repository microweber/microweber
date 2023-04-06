<div class="card-header">
    <div class="col d-flex justify-content-md-between justify-content-center align-items-center px-0">
        <div class="d-flex align-items-center">
            <i class="mdi mdi-earth text-primary fs-2"></i>

            <h1 class="d-md-flex d-none card-title">

                <a class="@if(isset($currentCategory) and $currentCategory) text-decoration-none @else text-decoration-none text-dark ms-1 @endif" onclick="livewire.emit('deselectAllCategories');return false;">
                    {{_e('Website')}}
                </a>

                @if(isset($currentCategory) and $currentCategory)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    {{$currentCategory['title']}}
                @endif

                @if($isInTrashed)
                    <span class="text-muted">&nbsp; &raquo; &nbsp;</span>
                    <i class="mdi mdi-trash-can"></i> {{ _e('Trash') }}
                @endif

            </h1>

            @if(isset($currentCategory) and $currentCategory)
                <a class="ms-1 text-muted fs-5"  onclick="livewire.emit('deselectAllCategories');return false;">
                    <i class="fa fa-times-circle"></i>
                </a>
            @endif
        </div>
        <div>

            @if(isset($contentType))
                @include('content::admin.content.livewire.create-content-buttons',['buttonClass'=>'btn btn-outline-primary btn-sm'])

            @endif




            <?php
            /*  @if(isset($currentCategory) and $currentCategory)
            <a href="{{category_link($currentCategory['id'])}}" target="_blank" title="{{_e('View category')}}"><span class="fa fa-external-link-alt"></span></a>
            @endif*/

            ?>



        </div>
    </div>
</div>

