<select class="form-control" onchange="if (this.value) window.location.href=this.value">
    @foreach($options as $option)
    <option value="{{$option->link}}">{{$option->name}}</option>
    @endforeach
</select>
