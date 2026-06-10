<div {{ $attributes->merge(['class' => 'col ' . $class]) }}>
    <div class="card h-100 rounded-3 shadow-sm{{ $highlighted ? ' border-primary' : '' }}">
        <div class="card-header py-3{{ $highlighted ? ' bg-primary text-white border-primary' : '' }}">
            <h4 class="my-0 fw-normal">{{ $planName }}</h4>
        </div>
        <div class="card-body">
            <h1 class="card-title pricing-card-title">{{ $price }}<small class="text-muted fw-light">{{ $period }}</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
                @foreach($features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            @if(isset($actions))
                {{ $actions }}
            @else
                <button type="button" class="{{ $highlighted ? 'btn btn-primary w-100' : $buttonStyle . ' w-100' }}">{{ $buttonText }}</button>
            @endif
        </div>
    </div>
</div>