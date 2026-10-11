@props(['submit'])

<div class="col-xxl-10 col-md-11 col-12 px-md-0 px-2 mx-auto">

    <div class="card mb-7">
        <div class="card-body">
            <div {{ $attributes->merge(['class' => 'row']) }}>

            <div class="col-xl-3 mb-xl-0 mb-3">

                <x-microweber-ui::section-title>
                    <x-slot name="title"><h5 class="font-weight-bold settings-title-inside">{{ $title }}</h5></x-slot>
                    <x-slot name="description">
                        <small class="text-muted">
                            {{ $description }}
                        </small>
                    </x-slot>
                </x-microweber-ui::section-title>
                </div>

                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <form wire:submit.prevent="{{ $submit }}">

                                       <div class="mb-4">
                                           {{ $form }}
                                       </div>


                                        @if (isset($actions))
                                            <div class="text-end">
                                                {{ $actions }}
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
