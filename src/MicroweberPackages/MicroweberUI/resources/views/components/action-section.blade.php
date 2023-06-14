<div class="col-xxl-10 col-md-11 col-12 px-md-0 px-2 mx-auto">

    <div class="card mb-7">
        <div class="card-body">
            <div {{ $attributes->merge(['class' => 'row']) }}>

                <div class="col-xl-3 mb-xl-0 mb-3">

                    <x-microweber-ui::section-title>
                        <x-slot name="title"><h5 class="font-weight-bold settings-title-inside">{{ $title }}</h5></x-slot>
                       @if(isset($description))
                        <x-slot name="description">
                            <small class="text-muted">
                                {{ $description }}
                            </small>
                        </x-slot>
                        @endif
                    </x-microweber-ui::section-title>
                </div>

                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    {{ $content }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
