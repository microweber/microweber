@if(isset($buttons))
<div class="manage-post-item-links">
    @foreach($buttons as $button)
    <a href="{{$button['href']}}" class="{{$button['class']}}">{{$button['name']}}</a>
    @endforeach
</div>
@endif
