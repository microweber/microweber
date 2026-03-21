@props(['value', 'for' => null])

@php
    $hasError = $for && ($errors->has($for) || $errors->has(str_replace('.', '_', $for)));
    $errorClass = $hasError ? ' text-danger-600 dark:text-danger-400' : '';
@endphp

<label {{ $attributes->merge(['class'=>'live-edit-label' . $errorClass]) }}>
    {{ $value ?? $slot }}
    @if($hasError)
        <span class="mw-field-error-indicator" aria-hidden="true">*</span>
    @endif
</label>
