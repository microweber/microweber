<div {{ $attributes->merge(['class' => 'col-sm-'.$colSm.' col-md-'.$col.' col-lg-'.$colLg.' col-xl-'.$colXl.' col-xxl-'.$colXxl.' '.$class]) }}>
    {{ $slot }}
</div>
