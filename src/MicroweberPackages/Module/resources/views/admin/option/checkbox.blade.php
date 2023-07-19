<div>
    @if(!empty($checkboxOptions))
        @foreach($checkboxOptions as $key => $option)
        <label class="form-check">
            <input class="form-check-input" type="checkbox">
            <span class="form-check-label">{{$option}}</span>
        </label>
    @endforeach
    @endif

</div>
