@if($label)
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
@endif

<select 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    id="{{ $name }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $attributes->merge(['class' => 'form-select']) }}
>
    @foreach($options as $value => $label)
        <option 
            value="{{ $value }}"
            @if($multiple && is_array($selected))
                {{ in_array($value, $selected) ? 'selected' : '' }}
            @else
                {{ $value == $selected ? 'selected' : '' }}
            @endif
        >
            {{ $label }}
        </option>
    @endforeach
</select>
