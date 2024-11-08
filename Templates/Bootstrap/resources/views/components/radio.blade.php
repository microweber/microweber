@php
    $attr = $attributes->merge(['class' => 'form-check-input']);
    if ($disabled) {
        $attr['disabled'] = 'disabled';
    }
    if(!isset($id) or !$id and isset($label)){
        $id = str_slug($label);
    }

@endphp

<div class="form-check">
    <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
           class="{{ $attr['class'] }}" {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
    @if(!empty($label))
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
