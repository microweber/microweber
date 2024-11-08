@php
  $attr = $attributes->merge(['class' => 'form-check-input']);
  if ($disabled) {
      $attr['disabled'] = 'disabled';
  }
@endphp

<div class="form-check">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" class="{{ $attr['class'] }}" {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
    @if(!empty($label))
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
