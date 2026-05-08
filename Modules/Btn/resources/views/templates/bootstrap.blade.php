@php
/*

type: layout

name: Bootstrap

description: Bootstrap button

*/
@endphp



@include('modules.components::module-data')



@include('modules.btn::components.custom-css')


@php
    $hasIcon = !empty($icon);
    $iconHtml = $hasIcon ? icon_html($icon) : '';
    $iconPosition = $iconPosition ?? 'left';

    /*
     * The settings page exposes options.align as left/center/right
     * (see Modules/Btn/Filament/BtnModuleSettings.php:59) but the
     * template never used it, so dropping a button into a wide
     * column always rendered as a small left-aligned pill. Map the
     * saved value to text-align via a wrapper div so the operator's
     * choice actually shows up.
     */
    $alignClass = match ($align ?? '') {
        'center' => 'text-center',
        'right'  => 'text-end',
        'left'   => 'text-start',
        default  => '',
    };
@endphp




<div class="mw-btn-align-wrap {{ $alignClass }}">
@if($action == 'submit')
<button type="submit" id="{{ $btnId }}" class="btn {{ $style . ' ' . $size . ' ' . $class}}" {!! $attributes !!}>
    @if($hasIcon && $iconPosition == 'left'){!! $iconHtml !!}@endif
    {{ $text }}
    @if($hasIcon && $iconPosition == 'right'){!! $iconHtml !!}@endif
</button>
@elseif($action == 'popup')
    @php
        // AI-56 / TICKET-CW (cycle-63 2026-05-08): defence-in-depth.
        // popupFunctionId is interpolated into a `href="javascript:{id}()"`
        // URI below — even though Blade {{ }} HTML-escapes, that does NOT
        // protect against JS-injection inside the URI scheme. Lock the
        // value to a strict JS-identifier shape at render time so the
        // template is safe regardless of which code path produced
        // popupFunctionId (BtnModule sanitises it too).
        $popupFunctionId = preg_replace('/[^A-Za-z0-9_]/', '', (string) $popupFunctionId);
    @endphp
    @include('modules.btn::components.popup')
    <a id="{{ $btnId }}" href="javascript:{{ $popupFunctionId }}()" class="btn {{ $style . ' ' . $size . ' ' . $class}}" {!! $attributes !!}>
        @if($hasIcon && $iconPosition == 'left'){!! $iconHtml !!}@endif
        {{ $text }}
        @if($hasIcon && $iconPosition == 'right'){!! $iconHtml !!}@endif
    </a>
@else
<a id="{{ $btnId }}" href="{{ $url }}" @if ($blank) target="_blank" @endif class="btn {{ $style . ' ' . $size . ' ' . $class}}" {!! $attributes !!}>
    @if($hasIcon && $iconPosition == 'left'){!! $iconHtml !!}@endif
    {{ $text }}
    @if($hasIcon && $iconPosition == 'right'){!! $iconHtml !!}@endif
</a>
@endif
</div>
