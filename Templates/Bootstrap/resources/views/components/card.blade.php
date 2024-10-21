<div class="card">
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
