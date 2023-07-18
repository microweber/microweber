<div>

    @if(!empty($radioOptions))
        @foreach($radioOptions as $key => $option)
    <label class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="radios-inline">
        <span class="form-check-label">{{$option}}</span>
    </label>
        @endforeach
    @endif

</div>
