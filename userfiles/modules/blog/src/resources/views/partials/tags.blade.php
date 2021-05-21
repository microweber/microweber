<div class="form-group">
<label><?php _e('Tags'); ?></label>
    <div class="tags">
@foreach($tags as $tag)
    <a href="{{$tag->url}}" @if(\Request::get('tags', false) == $tag->slug) style="border:3px solid #43a90c;" @endif>{{$tag->name}}</a>
@endforeach
</div>
</div>
