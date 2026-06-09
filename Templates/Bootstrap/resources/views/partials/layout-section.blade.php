{{--
    Reusable partial: wraps content in a layout section with optional
    background module, top/bottom spacer modules, and a container.

    Usage (via @component):
        @component('templates.bootstrap::partials.layout-section', [
            'params'          => $params,
            'classes'         => $classes,
            'sectionClass'    => 'section features-skin-2',
            'fieldName'       => 'layout-features-skin-1',
            'editableClass'   => 'edit safe-mode',
            'noDrop'          => false,
            'hasBackground'   => true,
            'backgroundAttrs' => '',
            'hasSpacers'      => true,
            'containerClass'  => 'mw-layout-container',
            'useContainer'    => true,
        ])
            ... your inner content here ...
        end-component

    All parameters except params and classes are optional with sensible defaults.
    NOTE: never write a nested Blade comment close-token inside this docblock —
    Blade's non-greedy comment parser would end the comment early and expose the
    rest as live code (symptom: "View [] not found").
--}}
@php
    $sectionClass   = $sectionClass   ?? 'section';
    $fieldName      = $fieldName      ?? '';
    $editableClass  = $editableClass  ?? 'edit safe-mode';
    $noDrop         = $noDrop         ?? false;
    $hasBackground  = $hasBackground  ?? true;
    $backgroundAttrs = $backgroundAttrs ?? '';
    $hasSpacers     = $hasSpacers     ?? true;
    $containerClass = $containerClass ?? 'mw-layout-container';
    $useContainer   = $useContainer   ?? true;

    // Compute padding/layout classes INLINE. A separate @include cannot be used
    // to set $layout_classes: @include renders in its own variable scope, so any
    // variable it assigns does NOT propagate back here — the padding defaults
    // (p-t-100 etc.) would be silently lost and $layout_classes would be undefined.
    $classes = $classes ?? [];
    if (empty($classes['padding_top']))    { $classes['padding_top']    = $defaultPaddingTop ?? ''; }
    if (empty($classes['padding_bottom'])) { $classes['padding_bottom'] = $defaultPaddingBottom ?? ''; }
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'];

    $sectionId = $params['id'] ?? '';

    // Build the section attribute list in PHP. Blade @if ... @endif must NOT
    // live inside an <x-component> opening tag (the component-tag compiler
    // splits the directive and leaves an unbalanced @endif). For the same
    // robustness this wrapper emits a literal <section>/<div> rather than the
    // class-based <x-section>/<x-container> — it adds nothing those literals
    // don't, and it sidesteps the "View [] not found" quirk of nesting
    // class-based components inside an @component slot while guaranteeing the
    // container class (mw-layout-container, allow-drop, paddings) is preserved.
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