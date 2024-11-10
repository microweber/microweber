<div {{ $attributes->merge(['class' => 'col-sm-'.$sizeSm.' col-md-'.$sizeMd.' col-lg-'.$sizeLg.' col-xl-'.$sizeXl.' col-xxl-'.$sizeXxl.' '.$class]) }}>
    {{ $slot }}
</div>
