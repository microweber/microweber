@php
    $classes = $classes ?? [];
    if (empty($classes['padding_top']))    { $classes['padding_top']    = $defaultPaddingTop ?? ''; }
    if (empty($classes['padding_bottom'])) { $classes['padding_bottom'] = $defaultPaddingBottom ?? ''; }
    $layout_classes = ($layoutClasses ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'];

    $sectionId = $params['id'] ?? '';

    $sectionClasses = trim($sectionClass . ' ' . trim($layout_classes) . ' ' . $editableClass . ($noDrop ? ' nodrop' : ''));
@endphp

<section class="{{ $sectionClasses }}"@if($fieldName) field="{{ $fieldName }}-{{ $sectionId }}" rel="module"@endif>
    @if($hasBackground)
        <module type="background" id="background-layout--{{ $sectionId }}" {!! $backgroundAttrs !!}/>
    @endif

    @if($hasSpacers)
        <module type="spacer" id="spacer-layout--{{ $sectionId }}-top"/>
    @endif

    @if($useContainer)
        <div class="container {{ $containerClass }}">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

    @if($hasSpacers)
        <module type="spacer" id="spacer-layout--{{ $sectionId }}-bottom"/>
    @endif
</section>
