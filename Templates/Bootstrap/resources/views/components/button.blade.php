<button 
    type="button" 
    class="btn {{ $outline ? 'btn-outline-' . $type : 'btn-' . $type }} {{ $size ? 'btn-' . $size : '' }} {{ $class }}" 
    {{ $disabled ? 'disabled' : '' }} 
    {{ $attributes }}>
    {{ $slot }}
</button>
