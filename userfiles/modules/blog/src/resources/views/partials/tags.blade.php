<div class="form-group">
<label><?php _e('Tags'); ?></label>
    <div class="tags">
@foreach($tags as $tag)
    <a href="?tags={{$tag->slug}}">{{$tag->name}}</a>
@endforeach
</div>
</div>
