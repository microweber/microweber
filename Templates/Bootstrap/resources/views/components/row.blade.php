<div {{ $attributes->merge(['class' => 'row ' . ($flex ? 'd-flex ' : '') . ($flexWrap ? 'flex-wrap ' : '') . ($flexNoWrap ? 'flex-nowrap ' : '') . $class]) }}>
    {{ $slot }}
</div>
