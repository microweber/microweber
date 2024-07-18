<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :state-path="$getStatePath()"
>

    <div x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }} }">
        {{ $getRecord()->provider }}
    </div>

</x-dynamic-component>
