<div {{ $attributes->merge(['class' => 'row']) }}>
    <div class="col-md-4">
        <x-user::section-title>
            <x-slot name="title">{{ $title }}</x-slot>
            <x-slot name="description">{{ $description }}</x-slot>
        </x-user::section-title>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                {{ $content }}
            </div>
        </div>
    </div>
</div>
