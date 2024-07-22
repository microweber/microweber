<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :state-path="$getStatePath()"
>

    <div x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }} }">
        {{ $getRecord() }}
    </div>

</x-dynamic-component>
