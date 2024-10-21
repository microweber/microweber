<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title edit">
            {{ $title ?? 'Default Header' }}
        </h5>
        <div class="edit">
            {{ $content }}
        </div>
    </div>
    <div class="card-footer">
        {{ $footer }}
    </div>
</div>
