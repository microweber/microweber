{{--
    Reusable partial: a single pricing-plan card column.

    Usage:
        @include('templates.bootstrap::partials.pricing-card', [
            'planName'   => 'Pro',
            'price'      => '$15',
            'period'     => '/mo',
            'features'   => ['20 users included', '10 GB of storage', ...],
            'btnId'      => $params['id'] . '-pro',
            'btnStyle'   => 'w-100 btn btn-lg btn-primary',
            'btnText'    => 'Get started',
            'highlighted'=> false,
            'headerClass'=> '',
        ])

    NOTE: pass button PARAMETERS (btnId/btnStyle/btnText), never a pre-rendered
    <module ...> string — Microweber's parser rewrites every "<module " token in
    the source, INCLUDING inside PHP string literals, which corrupts the array
    and fatals with: syntax error, unexpected identifier "module".
--}}
@php
    $highlighted  = $highlighted ?? false;
    $headerClass  = $headerClass ?? '';
    $period       = $period ?? '/mo';
    $btnStyle     = $btnStyle ?? 'w-100 btn btn-lg btn-outline-primary';
    $btnText      = $btnText ?? 'Choose plan';
    $btnId        = $btnId ?? null;
    $cardBorder   = $highlighted ? ' border-primary' : '';
    $headerBorder = $highlighted ? ' border-primary' : '';
@endphp

{{-- Bootstrap row-cols-* auto-layout requires a BARE .col child. <x-col>
     always emits col-sm-12…col-xxl-12 (width:100%), which would override the
     row-cols grid and stack every card full-width — so use a literal .col here. --}}
<div class="col">
    <x-card class="mb-4 rounded-3 shadow-sm{{ $cardBorder }}">
        <x-slot name="header">
            <div class="py-3 {{ $headerClass }}{{ $headerBorder }}">
                <h4 class="my-0 fw-normal">{{ $planName }}</h4>
            </div>
        </x-slot>
        <x-slot name="content">
            <h1 class="card-title pricing-card-title">{{ $price }}<small class="text-muted fw-light">{{ $period }}</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
                @foreach($features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            <module type="btn" @if($btnId) id="{{ $btnId }}" @endif button_style="{{ $btnStyle }}" button_text="{{ $btnText }}"/>
        </x-slot>
    </x-card>
</div>