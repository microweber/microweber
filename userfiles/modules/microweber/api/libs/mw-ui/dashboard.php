<?php include('partials/header.php'); ?>

<main>
    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>Statistics</strong></h5>
            <div>
                <nav class="nav btn-hover-style-2">
                    <a class="btn btn-outline-secondary btn-sm justify-content-center active" data-bs-toggle="tab" href="#stat-daily">Daily</a>
                    <a class="btn btn-outline-secondary btn-sm justify-content-center mx-2" data-bs-toggle="tab" href="#stat-weekly">Weekly</a>
                    <a class="btn btn-outline-secondary btn-sm justify-content-center mr-2" data-bs-toggle="tab" href="#stat-monthly">Monthly</a>
                    <a class="btn btn-outline-secondary btn-sm justify-content-center" data-bs-toggle="tab" href="#stat-yearly">Yearly</a>
                </nav>
            </div>
        </div>
        <div class="card-body">
            <div id="stats-tabs" class="tab-content py-3">
                <div class="tab-pane fade show active" id="stat-daily">
                    <h5>Daliy</h5>
                    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosbysweater
                        eu banh mi,qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                </div>

                <div class="tab-pane fade" id="stat-weekly">
                    <h5>Weekly</h5>
                    <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress,commodo
                        enim craftbeer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                </div>

                <div class="tab-pane fade" id="stat-monthly">
                    <h5>Monthly</h5>
                    <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksyhoodie
                        helvetica.DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                </div>

                <div class="tab-pane fade" id="stat-yearly">
                    <h5>Yearly</h5>
                    <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksyhoodie
                        helvetica.DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                </div>
            </div>
            <hr class="thin">

            <div class="row d-flex justify-content-between">
                <div class="col-6 col-sm d-flex align-items-center justify-content-center justify-content-sm-start">
                    <i class="mdi mdi-eye mdi-24px text-muted"></i> <span class="text-primary mx-2">6</span> <span>Views</span>
                </div>

                <div class="col-6 col-sm d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-account-multiple mdi-24px text-muted"></i> <span class="text-primary mx-2">1</span> <span>Visitors</span>
                </div>

                <div class="col-6 col-sm d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-shopping mdi-24px text-muted"></i> <span class="text-primary mx-2">0</span> <span>Orders</span>
                </div>

                <div class="col-6 col-sm d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-comment-account mdi-24px text-muted"></i> <span class="text-primary mx-2">1</span> <span>Comments</span>
                </div>

                <div class="col-12 col-sm d-flex align-items-center  justify-content-center justify-content-sm-end">
                    <a class="btn btn-outline-secondary btn-sm btn-rounded">Show more</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong>Recent orders</strong></h5>
            <div><a href="#" class="btn btn-primary btn-sm"><span class="badge badge-success badge-pill mr-2 absolute-left">4</span> New orders</a> <a href="#" class="btn btn-outline-secondary btn-sm">Add new order</a></div>
        </div>
        <div class="card-body">
            <h6><span class="badge badge-success badge-sm badge-pill mr-2">1</span> <strong>New orders</strong></h6>

            <div class="card mb-2 not-collapsed-border collapsed card-success card-order-holder" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapseExample">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row align-items-center">
                                <div class="col item-image">
                                    <div class="img-circle-holder">
                                        <img src="http://templates.microweber.com/new-world/userfiles/cache/thumbnails/1920/tn-a47c56a9d6071b0cb5e396486f08807a.jpg" />
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

                    <div class="collapse" id="collapse-3">
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

            <div class="card mb-2 not-collapsed-border collapsed card-danger card-order-holder" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapseExample">
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

                    <div class="collapse" id="collapse-4">
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
            <br />

            <h6><strong>All orders</strong></h6>
            <div class="card mb-2 not-collapsed-border collapsed card-order-holder bg-silver" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapseExample">
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

                    <div class="collapse" id="collapse-1">
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

            <div class="card mb-2 not-collapsed-border collapsed card-order-holder bg-silver" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapseExample">
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

                    <div class="collapse" id="collapse-2">
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


            <div class="icon-title">
                <i class="mdi mdi-shopping"></i> <h5>You don't have any orders</h5>
            </div>
        </div>
    </div>

    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-comment-account text-primary mr-3"></i> <strong>Last comments</strong></h5>
            <div><a href="#" class="btn btn-primary btn-sm">View all</a></div>
        </div>
        <div class="card-body">
            <div class="card mb-2 not-collapsed-border collapsed bg-silver" data-bs-toggle="collapse" data-bs-target="#comments-1" aria-expanded="false" aria-controls="collapseExample">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="max-width: 100px;">
                            <div class="img-circle-holder img-absolute border-radius-0 border-0">
                                <img src="http://templates.microweber.com/new-world/userfiles/cache/thumbnails/800/tn-1f6fb5c5df393ae432d222e0ae453079.jpg" />
                            </div>
                        </div>
                        <div class="col text-start text-left">
                            <h5 class="text-primary text-break-line-2">Around the world</h5>
                        </div>

                        <div class="col-12 col-sm text-end text-right">5 minutes ago</div>
                    </div>
                    <div class="collapse" id="comments-1">
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="dashboard.html" class="btn btn-primary btn-sm btn-rounded">View order</a>
                            </div>
                        </div>
                        <hr class="thin" />
                        <div class="row">
                            <div class="col-md-12">


                                <div class="row mb-3">
                                    <div class="col" style="max-width: 80px;">
                                        <div class="img-circle-holder w-60 border-0 border-radius-10">
                                            <img src="https://d1qb2nb5cznatu.cloudfront.net/users/40837-medium_jpg?1405468137" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-1"><small class="text-muted">Status</small></div>
                                        <div>
                                            <select class="selectpicker js-change-color d-inline-block" data-style="btn-success btn-sm" data-width="fit">
                                                <option data-change-color="btn-success">Publish</option>
                                                <option data-change-color="btn-warning">Unpublish</option>
                                                <option data-change-color="btn-secondary">Mark as Spam</option>
                                                <option data-change-color="btn-danger">Delete</option>
                                            </select>

                                            <a href="dashboard.html" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        </div>
                                    </div>
                                </div>


                                <h6><strong>Boris Sokolov</strong> <small class="text-muted">says:</small></h6>
                                <div>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                </div>
                                <a href="#reply-message-1" class="btn btn-outline-secondary btn-sm icon-left js-show-more"><i class="mdi mdi-comment-account text-primary"></i> Reply</a>

                                <div class="collapse" id="reply-message-1">
                                    <hr class="thin" />

                                    <div class="row mb-3">
                                        <div class="col-12 mb-3">
                                            <h5><strong>Add a new comment</strong></h5>
                                        </div>
                                        <div class="col" style="max-width: 80px;">
                                            <div class="img-circle-holder w-60 border-0 border-radius-10">
                                                <img src="https://d1qb2nb5cznatu.cloudfront.net/users/40837-medium_jpg?1405468137" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm mt-3 mt-sm-0">
                                            <div class="form-group">
                                                <textarea></textarea>
                                            </div>
                                            <div class="text-end text-right">
                                                <a href="dashboard.html" class="btn btn-outline-secondary btn-sm">Post Comment</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="icon-title">
                <i class="mdi mdi-comment-account"></i> <h5>You don't have any comments</h5>
            </div>
        </div>
    </div>

    <div class="card style-1 mb-3 card-message-holder">
        <div class="card-header">
            <h5><i class="mdi mdi-email-check text-primary mr-3"></i> <strong>Recent Messages</strong></h5>
            <div><a href="#" class="btn btn-outline-secondary btn-sm">View all</a></div>
        </div>
        <div class="card-body">
            <div class="card mb-2 not-collapsed-border collapsed bg-silver" data-bs-toggle="collapse" data-bs-target="#message-1" aria-expanded="false" aria-controls="collapseExample">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="max-width:26px;">
                            <i class="mdi mdi-email text-primary mdi-24px"></i>
                        </div>
                        <div class="col-10 col-sm item-id"><span class="text-primary">#4</span></div>
                        <div class="col-6 col-sm">Mar 05, 2020 <small class="text-muted">12:33h</small></div>
                        <div class="col-6 col-sm">44 minutes ago</div>
                    </div>
                    <div class="collapse" id="message-1">
                        <hr class="thin" />
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Fields</strong></h6>
                                <div>
                                    <small class="text-muted">Your name:</small>
                                    <p>Boris Sokolov</p>
                                </div>

                                <div>
                                    <small class="text-muted">E-mail address:</small>
                                    <p>sokolov.boris@gmail.com</p>
                                </div>

                                <div>
                                    <small class="text-muted">Phone:</small>
                                    <p>0878 999 888</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>&nbsp;</h6>

                                <div>
                                    <small class="text-muted">Message:</small>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                </div>

                                <div>
                                    <small class="text-muted">Attached files:</small>
                                    <p><i class="mdi mdi-pdf-box text-primary mdi-18px"></i> Refactoring UI: Bad About</p>
                                    <p><i class="mdi mdi-file-check text-primary mdi-18px"></i> Some of our files attached</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="icon-title">
                <i class="mdi mdi-email-check"></i> <h5>You don't have any messages</h5>
            </div>
        </div>
    </div>


    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-link text-primary mr-3"></i> <strong>Quick links</strong></h5>
        </div>
        <div class="card-body">
            <div class="row quick-links">
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-bell"></i> Notifications</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-earth"></i> Manage Website</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-fruit-cherries"></i> Go to Marketplace</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-shopping"></i> View Orders</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-view-grid-plus"></i> Manage Modules</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-update"></i> Updates</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-comment-account"></i> Comments</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-file-check"></i> File Manager</a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#" class="btn btn-link"><i class="mdi mdi-penguin"></i> Suggest a Feature</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row copyright mt-3">
        <div class="col-12">
            <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
        </div>
    </div>
</main>


<?php include('partials/footer.php'); ?>
