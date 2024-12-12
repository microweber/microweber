<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header url="{{site_url()}}">
@php
    $logo = get_option('logo', 'email');
@endphp

@if($logo)
    <img src="{{ $logo }}" alt="logo" height="34px" />
@else
    {{ config('app.name') }}
@endif
</x-mail::header>
</x-slot:header>


{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
$color = match ($level) {
'success', 'error' => $level,
default => 'primary',
};
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards,')<br>
{{ get_option('website_title', 'website') }} Team
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
"If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser:',
[
'actionText' => $actionText,
]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset


{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ get_option('website_title', 'website') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>


</x-mail::layout>
