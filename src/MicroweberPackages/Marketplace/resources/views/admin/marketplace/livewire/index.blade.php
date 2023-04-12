<div>
    <div class="container px-5">

        <div class="card mb-3 ">
            <div class="card-header justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-fruit-cherries fs-2 text-primary mr-3"></i> <strong>Marketplace</strong>
                </h5>
                <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0">
                    <div class="input-icon">
                              <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg></span>
                        <input type="text" class="form-control" placeholder="Search..." wire:keydown.enter="filter" wire:model.lazy="keyword" />
                        <div wire:loading wire:target="keyword" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Searching...</span>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-primary" wire:click="reloadPackages">
                        <div wire:loading wire:target="reloadPackages" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Reload Packages
                    </button>
                </div>
            </div>

            <div class="card-body p-3">
              <div class="row g-4">

                <div class="col-12">
                    <div>
                        <p>Welcome to the marketplace Here you will find new modules, templates and updates</p>
                    </div>
                    <div class="btn-group d-flex">
                        <button type="button" class="btn @if($category == 'all') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('all')">
                            <div wire:loading wire:target="filterCategory('all')" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>  All
                        </button>
                        <button type="button" class="btn @if($category == 'microweber-template') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('microweber-template')">
                            <div wire:loading wire:target="filterCategory('microweber-template')" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="mdi mr-1 mdi-pencil-ruler"></i> Templates
                        </button>
                        <button type="button" class="btn @if($category == 'microweber-module') btn-primary @else btn-outline-primary @endif" wire:click="filterCategory('microweber-module')">
                            <div wire:loading wire:target="filterCategory('microweber-module')" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="mdi mr-1 mdi-view-grid-plus"></i> Modules
                        </button>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row row-cards">

                        @if(!empty($marketplacePagination))
                        @foreach($marketplacePagination as $marketItem)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-sm">

                                @if(isset($marketItem['extra']['_meta']['screenshot']))
                                <a href="#" class="d-block">
                                    @if($marketItem['type'] == 'microweber-module')
                                        <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 180px;background-repeat:no-repeat;background-size: contain;background-position: center;" class="card-img-top">
                                        </div>
                                        @else
                                        <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 180px;background-size: cover;background-position: top;" class="card-img-top">
                                        </div>
                                        @endif
                                </a>
                                @else
                                    <a href="#" class="d-block">
                                        <div class="card-img-top text-center">
                                            <i class="mdi mdi-view-grid-plus text-muted"
                                               style="opacity:0.5;font-size:126px;margin-left: 15px;"></i>
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
                            <div class="d-flex justify-content-center mt-4">
                                {!! $marketplacePagination->links('livewire-tables::specific.bootstrap-4.pagination') !!}
                            </div>
                        @else
                            <div class="col-12">
                            no items
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            </div>
        </div>


    </div>
</div>
