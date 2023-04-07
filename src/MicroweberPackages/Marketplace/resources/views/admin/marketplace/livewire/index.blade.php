<div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-3">
                    <form action="./" method="get" autocomplete="off" novalidate="">
                        <div class="subheader mb-2">Category</div>
                        <div class="list-group list-group-transparent mb-3">
                            <a class="list-group-item list-group-item-action d-flex align-items-center active" href="#">
                                Templates
                                <small class="text-muted ms-auto">0</small>
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="#">
                                Modules
                                <small class="text-muted ms-auto">0</small>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="col-9">
                    <div class="row row-cards">

                        @foreach($marketplace as $marketItem)
                        <div class="col-sm-6 col-lg-6">
                            <div class="card card-sm">

                                @if(isset($marketItem['extra']['_meta']['screenshot']))
                                <a href="#" class="d-block">
                                    <div style="background-image:url({{$marketItem['extra']['_meta']['screenshot']}});width: 100%;height: 300px;background-size: cover;background-position: top;" class="card-img-top">
                                    </div>
                                </a>
                                @else
                                    <a href="#" class="d-block">
                                    <div class="card-img-top">
                                        <i class="mdi mdi-view-grid-plus text-muted"
                                           style="opacity:0.5;font-size:90px;margin-left: 15px;"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <div class="d-flex align-items-center">
<!--                                        <span class="avatar me-3 rounded" style="background-image: url(./static/avatars/000m.jpg)"></span>-->
                                        <div>
                                            <div>
                                                {{$marketItem['description']}}
                                            </div>
                                            <div class="text-muted">3 days ago</div>
                                        </div>
                                    </div>
                                    {{--@dump($marketItem)--}}
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
