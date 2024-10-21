<div class="card

    @if($theme == 'dark')
        bg-dark text-white
    @endif
     @if($theme == 'danger')
        bg-danger text-white
    @endif
     @if($theme == 'success')
        bg-success text-white
    @endif

 ">
    <div class="card-body">
        @if(isset($title))
        <h5 class="card-title edit">
            {{ $title }}
        </h5>
        @endif
        @if(isset($content))
        <div class="edit">
            {{ $content }}
        </div>
        @endif
    </div>
    @if(isset($footer))
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endif
</div>
