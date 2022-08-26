<a href="{{$path['href']}}" target="{{$path['target']}}">
    <img src="{{$path['location']}}" {!! count($attributes) ? $column->arrayToAttributes($attributes) : '' !!} />
</a>
