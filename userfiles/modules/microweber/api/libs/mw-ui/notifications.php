<?php include('partials/header.php'); ?>

<main>

    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-bell text-primary mr-3"></i> <strong>Notifications</strong></h5>
            <!--<div><a href="#" class="btn btn-primary btn-sm"><span class="badge badge-success badge-pill mr-2 absolute-left">4</span> New orders</a> <a href="#" class="btn btn-outline-secondary btn-sm">Add new order</a></div>-->
        </div>
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col-12 text-center text-sm-left">
                    <h5><strong>All Activities</strong></h5>
                    <p>List of all notifications of your website. <a href="#" class="d-block d-sm-block float-sm-right">Show system log</a></p>
                </div>
                <div class="col-12 d-sm-flex align-items-center justify-content-between">
                    <div class="text-center text-md-left my-2">
                        <div class="custom-control custom-checkbox d-inline-block mb-0 mr-3">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                            <label class="custom-control-label" for="customCheck1">Select all</label>
                        </div>

                        <button class="btn btn-outline-success btn-sm mr-1 mr-lg-2 btn-lg-only-icon"><i class="mdi mdi-email-open"></i> <span class="d-none d-xl-block">Mark as read</span></button>
                        <button class="btn btn-outline-warning btn-sm mr-1 mr-lg-2 btn-lg-only-icon"><i class="mdi mdi-email"></i> <span class="d-none d-xl-block">Mark as unread</span></button>
                        <button class="btn btn-outline-danger btn-sm mr-1 mr-lg-2 btn-lg-only-icon"><i class="mdi mdi-delete"></i> <span class="d-none d-xl-block">Delete selected</span></button>
                    </div>

                    <div class="float-sm-right text-center text-md-right my-2">
                        <span class="d-inline d-sm-none d-md-inline">Show notifications</span>
                        <select class="selectpicker" data-style="btn-sm" data-width="auto">
                            <option>All notifications</option>
                            <option>Orders</option>
                            <option>Comments</option>
                            <option>Messages</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="timeline">

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line datetime-indicator">
                        <button type="button" class="btn btn-primary btn-rounded btn-sm">Past hour</button>
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="check-1" checked="">
                            <label class="custom-control-label" for="check-1"></label>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-shopping"></i></button>
                    </div>
                    <div class="col">
                        <div class="card mb-2 not-collapsed-border collapsed card-bubble card-order-holder bg-silver" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapseExample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="row align-items-center">
                                            <div class="col item-image">
                                                <div class="img-circle-holder w-60">
                                                    <img src="https://s12emagst.akamaized.net/products/14414/14413292/images/res_77ef39ad42fe97f3288ebb6ed0e91dad_450x450_5rk5.jpg" />
                                                </div>
                                            </div>
                                            <div class="col item-id"><span class="text-primary">#4</span></div>
                                            <div class="col item-title" style="min-width: 210px;">
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
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line datetime-indicator">
                        <button type="button" class="btn btn-primary btn-rounded btn-sm mr-3">Today</button>

                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm btn-icon"><i class="mdi mdi-email-check"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm btn-icon mx-1"><i class="mdi mdi-shopping"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm btn-icon"><i class="mdi mdi-comment-account"></i></button>
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="check-12" >
                            <label class="custom-control-label" for="check-12"></label>
                        </div>

                        <span class="dot btn btn-primary"></span>
                    </div>
                    <div class="col">
                        <div class="row align-items-center mb-4" style="margin-top: -10px;">
                            <div class="col item-image" style="max-width: 55px;">
                                <div class="img-circle-holder img-absolute w-40">
                                    <img src="https://s12emagst.akamaized.net/products/14414/14413292/images/res_77ef39ad42fe97f3288ebb6ed0e91dad_450x450_5rk5.jpg" />
                                </div>
                            </div>
                            <div class="col-4"><span class="text-primary">Peter Ivanov</span> <span class="text-muted">joined the ocmmunity</span></div>
                            <div class="col-4"><span class="text-muted"><i class="mdi mdi-clock-outline"></i> 59 minutes, 39 seconds ago</span></div>
                        </div>
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="check-11" checked="">
                            <label class="custom-control-label" for="check-11"></label>
                        </div>

                        <span class="dot btn btn-primary"></span>
                    </div>
                    <div class="col">
                        <div class="row align-items-center mb-4" style="margin-top: -10px;">
                            <div class="col item-image" style="max-width: 55px;">
                                <div class="img-circle-holder w-40">
                                    <i class="mdi mdi-account-check text-primary mdi-24px"></i>
                                </div>
                            </div>
                            <div class="col-4"><span class="text-primary">Peter Ivanov</span> <span class="text-muted">joined the ocmmunity</span></div>
                            <div class="col-4"><span class="text-muted"><i class="mdi mdi-clock-outline"></i> 59 minutes, 39 seconds ago</span></div>
                        </div>
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="check-2">
                            <label class="custom-control-label" for="check-2"></label>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-email-check"></i></button>
                    </div>
                    <div class="col">
                        <div class="card mb-2 not-collapsed-border collapsed card-message-holder card-bubble bg-silver" data-bs-toggle="collapse" data-bs-target="#message-1" aria-expanded="false" aria-controls="collapseExample">
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col text-start text-left">
                                        <span class="text-primary text-break-line-2">New form entry</span>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col" style="max-width:55px;">
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
                    </div>
                </div>

                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="check-3" >
                            <label class="custom-control-label" for="check-3"></label>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-comment-account"></i></button>
                    </div>
                    <div class="col">
                        <div class="card mb-2 not-collapsed-border collapsed card-bubble bg-silver" data-bs-toggle="collapse" data-bs-target="#comments-1" aria-expanded="false" aria-controls="collapseExample">
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col text-start text-left">
                                        <span class="text-primary text-break-line-2">New comment</span>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col" style="max-width: 100px;">
                                        <div class="img-circle-holder img-absolute border-radius-0 border-0">
                                            <img src="https://s12emagst.akamaized.net/products/14414/14413292/images/res_77ef39ad42fe97f3288ebb6ed0e91dad_450x450_5rk5.jpg" />
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
                    </div>
                </div>




                <!--                <div class="row timeline-event">
                                    <div class="col pr-0 timeline-line">
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" class="custom-control-input" id="check-4" >
                                            <label class="custom-control-label" for="check-4"></label>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                    </div>
                                    <div class="col">

                                    </div>
                                </div>-->


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
