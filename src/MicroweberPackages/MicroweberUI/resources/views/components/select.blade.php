@props(['options'=>[]])

<label class="form-control-live-edit-label-wrapper">

    <select {!! $attributes->merge(['class'=>'form-select form-control-live-edit-input']) !!} >
        <option value="" disabled="disabled">Select option</option>
        @if(!empty($options))
            @foreach($options as $key => $option)
                <option value="{{ $key }}">{{ $option }}</option>
            @endforeach
        @endif
    </select>

    <span class="form-control-live-edit-bottom-effect"></span>

</label>
