<?php include('partials/header.php'); ?>

    <main>
        <div class="main-toolbar">
            <a href="#" class="btn btn-link text-silver px-0"><i class="mdi mdi-chevron-left"></i> Back to orders</a>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-header">
                <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong>Order #1</strong></h5>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Edit order</a>
                </div>
            </div>
            <div class="card-body">
                <h5 class="font-weight-bold">Order Information</h5>
                <div class="table-responsive">
                    <table class="table vertical-align-middle table-header-no-border table-primary-hover">
                        <thead class="text-primary">
                        <tr>
                            <th scope="col" class="font-weight-normal">Image</th>
                            <th scope="col" class="font-weight-normal">Product</th>
                            <th scope="col" class="font-weight-normal">SKU</th>
                            <th scope="col" class="font-weight-normal">Price</th>
                            <th scope="col" class="font-weight-normal">QTY</th>
                            <th scope="col" class="font-weight-normal">Total</th>
                            <th scope="col" class="font-weight-normal"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">
                                <img src="assets/img/no-image.jpg"/>
                            </th>
                            <td>
                                Web camera Samsung - <br/>HD picture 1080 x 1024 with <br/>USD port and internal memory
                            </td>
                            <td><span class="text-muted">#112212U-K</span></td>
                            <td>$3,558.99</td>
                            <td>1</td>
                            <td>$3,558.99</td>
                            <td><a href="#" class="text-muted" data-bs-toggle="tooltip" data-title="Remove"><i class="mdi mdi-trash-can-outline mdi-20px"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <img src="assets/img/no-image.jpg"/>
                            </th>
                            <td>
                                Web camera Samsung - <br/>HD picture 1080 x 1024 with <br/>USD port and internal memory
                            </td>
                            <td><span class="text-muted">#112212U-K</span></td>
                            <td>$3,558.99</td>
                            <td>1</td>
                            <td>$3,558.99</td>
                            <td><a href="#" class="text-muted" data-bs-toggle="tooltip" data-title="Remove"><i class="mdi mdi-trash-can-outline mdi-20px"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <img src="assets/img/no-image.jpg"/>
                            </th>
                            <td>
                                Web camera Samsung - <br/>HD picture 1080 x 1024 with <br/>USD port and internal memory
                            </td>
                            <td><span class="text-muted">#112212U-K</span></td>
                            <td>$3,558.99</td>
                            <td>1</td>
                            <td>$3,558.99</td>
                            <td><a href="#" class="text-muted" data-bs-toggle="tooltip" data-title="Remove"><i class="mdi mdi-trash-can-outline mdi-20px"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td colspan="5"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="info-table col-md-8 col-lg-6 col-xl-5 ml-auto">
                        <div class="row d-none">
                            <div class="col-6"></div>
                            <div class="col-6"></div>
                        </div>
                        <div class="row border-0">
                            <div class="col-6">
                                <strong>Total Amount</strong>
                            </div>
                            <div class="col-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Subtotal
                            </div>
                            <div class="col-6">
                                $ 30.99
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Shipping price
                            </div>
                            <div class="col-6">
                                $ 0.00
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Total</strong>
                            </div>
                            <div class="col-6">
                                <strong>$ 30.99</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="font-weight-bold">Client Information</h5>

                    <small>Edit client information <a href="#" class="btn btn-sm btn-outline-primary ml-2 text-dark">Edit</a></small>

                </div>

                <div class="info-table">
                    <div class="row">
                        <div class="col-6">
                            <span class="text-primary">Customer name</span>
                        </div>
                        <div class="col-6">
                            Boris Hristov Sokolov
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span class="text-primary">Email</span>
                        </div>
                        <div class="col-6">
                            sokolov.boris@gmail.com
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span class="text-primary">Phone number</span>
                        </div>
                        <div class="col-6">
                            +359 878 111 222
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span class="text-primary">User IP</span>
                        </div>
                        <div class="col-6">
                            192.168.0.1
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-body">
                <h5 class="mb-4 font-weight-bold">Shipping Address</h5>

                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Country:</strong> Bulgaria
                        </div>
                        <div class="mb-2">
                            <strong>City:</strong> Sofia
                        </div>
                        <div class="mb-2">
                            <strong>State:</strong> Sofia
                        </div>
                        <div class="mb-2">
                            <strong>Post code:</strong> 1000
                        </div>
                        <div class="mb-2">
                            <strong>Address:</strong> bul. Cherni Vrah 47
                        </div>
                        <div class="mb-4">
                            <strong>Phone:</strong> 0888 888 888
                        </div>
                        <div class="mb-2">
                            <strong>Additional information:</strong> <br/>
                            <small class="text-muted">No additional information regarding delivery specification of this order.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11730.055092107177!2d23.317036496045755!3d42.69284091665393!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40aa856d89cec335%3A0x761e70b801bf223b!2zT2xkIENpdHkgQ2VudGVyLCDQodC-0YTQuNGP!5e0!3m2!1sbg!2sbg!4v1590136474573!5m2!1sbg!2sbg"
                                width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-4 font-weight-bold">Shipping information</h5>

                        <div class="mb-4">
                            <strong>Provider:</strong> ECONT Bulgaria
                            <div class="d-inline ml-3">
                                <select class="selectpicker" data-style="btn-sm" data-width="150px">
                                    <option>ECONT Bulgaria</option>
                                    <option>Speedy</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <strong>Shipping number:</strong> 112-22334-44<br/>
                            <small class="text-muted">Shipping traking number</small>
                        </div>

                        <div class="mb-4">
                            <strong>Shipping status:</strong><br/>
                            <small class="text-muted">Shipping Status</small>
                        </div>

                        <div class="mb-4">
                            <strong>Additional information:</strong><br/>
                            <small class="text-muted">No additional information regarding delivery specification of this order.</small>
                        </div>
                    </div>

                    <div class="col-md-6 text-md-right">
                        <small>Edit shipment information <a href="#" class="btn btn-sm btn-outline-primary ml-2 text-dark">Edit</a></small>
                        <br/>
                        <br/>
                        <img src="assets/img/shipping_EcontExpress.jpg"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-body">

                <div class="row d-flex">
                    <div class="col-md-6">
                        <h5 class="mb-3 font-weight-bold">Order Status</h5>

                        <div class="mb-2">
                            <strong>What is the status of this order?</strong>
                        </div>
                        <div class="mb-2">
                            <select class="selectpicker" data-style="btn-sm" data-width="100%">
                                <option>Pending
                                    <small class="text-muted">(the order is not finished yet)</small>
                                </option>
                                <option>Sent
                                    <small class="text-muted">(the order is not finished yet)</small>
                                </option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Set additional information to your order, helps you to track the order status more effective</small>
                        </div>
                    </div>

                    <div class="col-md-6 border-left">
                        <h5 class="mb-3 font-weight-bold">Payment Information</h5>

                        <div class="mb-3">
                            <strong>Payment Method:</strong> paypal
                        </div>

                        <div class="mb-3">
                            <strong>Is paid:</strong>
                            <div class="d-inline">
                                <select class="selectpicker" data-style="btn-sm" data-width="100px">
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Payment amount:</strong> $30.99
                        </div>

                        <div class="mb-3">
                            <strong>Payment currency:</strong> USD
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light style-1 mb-3">
            <div class="card-body">
                <h5 class="mb-3 font-weight-bold">Invoices</h5>

                <div class="info-table">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <span class="text-primary">Invoice SAJ/2020/003</span>
                        </div>
                        <div class="col-md-6 text-end text-right">
                            <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                        </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <span class="text-primary">Invoice SAJ/2020/003</span>
                        </div>
                        <div class="col-md-6 text-end text-right">
                            <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                        </div>
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
