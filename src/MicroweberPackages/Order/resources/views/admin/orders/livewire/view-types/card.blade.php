@foreach ($orders as $order)

    @php
        $carts = $order->cart()->with('products')->get();
    @endphp
    <div class="card mb-2 not-collapsed-border collapsed card-order-holder bg-silver" data-bs-toggle="collapse" href="#collapse-1111" role="button" aria-expanded="false" aria-controls="collapse-1111">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col item-image">
                        <div class="img-circle-holder">
                            <img src="http://templates.microweber.com/new-world/userfiles/cache/thumbnails/800/tn-1f6fb5c5df393ae432d222e0ae453079.jpg" />
                        </div>
                    </div>
                    <div class="col item-id"><span class="text-primary">#4</span></div>
                    <div class="col item-title">
                        <span class="text-primary text-break-line-2">3D Sound Speaker 3D Sound Speaker 3D Sound Speaker 3D Sound Speaker 3D Sound Speaker</span>
                        <small class="text-muted">Ordered by: Boris Sokolov</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row align-items-center">

                    <div class="col-6 col-sm-4 col-md item-amount">$20 USD<br /><small class="text-muted">Paid</small></div>
                    <div class="col-6 col-sm-4 col-md item-date">Mar 05, 2020<br /><small class="text-muted">12:33h</small></div>
                    <div class="col-12 col-sm-4 col-md item-status"><span class="text-success">New</span><br /><small class="text-muted">&nbsp;</small></div>
                </div>
            </div>
        </div>

        <div class="collapse" id="collapse-1111">
            <div class="row mt-3">
                <div class="col-12 text-center text-sm-left">
                    <a href="dashboard.html" class="btn btn-primary btn-sm btn-rounded">View order</a>
                </div>
            </div>
            <hr class="thin" />
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h6><strong>Customer Information</strong></h6>
                    <div>
                        <small class="text-muted">Client name:</small>
                        <p>Boris Sokolov</p>
                    </div>

                    <div>
                        <small class="text-muted">E-mail:</small>
                        <p>sokolov.boris@gmail.com</p>
                    </div>

                    <div>
                        <small class="text-muted">Phone:</small>
                        <p>0878 999 888</p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Payment Information</strong></h6>

                    <div>
                        <small class="text-muted">Amount:</small>
                        <p>$20 USD</p>
                    </div>

                    <div>
                        <small class="text-muted">Payment method:</small>
                        <p>Pay on delivery</p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Shipping Information</strong></h6>

                    <div>
                        <small class="text-muted">Shipping method:</small>
                        <p>DHL</p>
                    </div>

                    <div>
                        <small class="text-muted">Address:</small>
                        <p>Bulgaria, Sofia 100, bul. Cherni Vrah 47</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach
