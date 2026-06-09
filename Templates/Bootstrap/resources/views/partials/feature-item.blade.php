{{--
    Reusable partial: a single feature column with icon, text and CTA button.

    Usage:
        @include('templates.bootstrap::partials.feature-item', [
            'iconClass'   => 'mw-micon-Add-User',
            'text'        => 'Feature description...',
            'buttonId'    => 'unique-btn-id',
            'buttonStyle' => 'btn-dark',
            'buttonSize'  => 'btn-md',
            'buttonText'  => 'Learn More',
            'colClass'    => 'col-md-6 col-lg-4 col-12',
        ])
--}}
@php
    $colClass    = $colClass    ?? 'col-md-6 col-lg-4 col-12';
    $buttonStyle = $buttonStyle ?? 'btn-dark';
    $buttonSize  = $buttonSize  ?? 'btn-md';
    $buttonText  = $buttonText  ?? 'Learn More';
@endphp

<div class="mx-auto {{ $colClass }} mb-5 cloneable element text-center safe-mode background-color-element">
    <i class="features-skin-2-icons mb-2 safe-element no-typing {{ $iconClass }}"></i>

    <div class="text-center mt-6 regular-mode">
        <p data-mwplaceholder="Enter text here">{{ $text }}</p>
    </div>
    <div class="mt-md-4 mt-3">
        <module type="btn" id="{{ $buttonId }}" button_style="{{ $buttonStyle }}" button_size="{{ $buttonSize }}" button_text="{{ $buttonText }}"/>
    </div>
</div>