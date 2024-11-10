@php
  $attr = $attributes->merge(['class' => 'form-control']);
  if ($disabled) {
      $attr['disabled'] = 'disabled';
  }
  if ($required) {
      $attr['required'] = 'required';
  }
@endphp

<div class="mb-3">
    @if(!empty($label))
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {!! $attr !!}>

    @if(!empty($help))
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>
