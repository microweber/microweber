<div>
    <div class="container">
        <div class="row g-4">

            <div class="col-12">
                <button type="button" class="btn btn-primary" wire:click="filterCategory('all')">All</button>
                <button type="button" class="btn btn-outline-primary" wire:click="filterCategory('microweber-template')">Templates</button>
                <button type="button" class="btn btn-outline-primary" wire:click="filterCategory('microweber-module')">Modules</button>
            </div>

            <div class="col-12">
                <div class="row row-cards">

                    @foreach($marketplace as $marketItem)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">

                            @if(isset($marketItem['extra']['_meta']['screenshot']))
                            <a href="#" class="d-block">
                                <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 280px;background-size: cover;background-position: top;" class="card-img-top">
                                </div>
                            </a>
                            @else
                                <a href="#" class="d-block">
                                    <div class="card-img-top">
                                        <i class="mdi mdi-view-grid-plus text-muted"
                                           style="opacity:0.5;font-size:90px;margin-left: 15px;"></i>
                                    </div>
                                </a>
                            @endif

                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div>
                                            {{$marketItem['description']}}
                                        </div>
                                        <div class="text-muted">
                                            Version: {{$marketItem['version']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
