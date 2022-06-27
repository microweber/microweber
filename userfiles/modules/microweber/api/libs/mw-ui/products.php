<?php include('partials/header.php'); ?>


<div class="tree">
    DURVO
</div>

<script>
    $(document).ready(function () {
        $('body > .main').addClass('show-sidebar-tree');
    });
</script>

<main>
    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>Shop</strong></h5>
            <div>
                <div class="input-group mb-0 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm" aria-label="Search" placeholder="Search">
                </div>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="toolbar row mb-3">
                <div class="col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-start my-1">
                    <div class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" class="custom-control-input" id="check-all" checked>
                        <label class="custom-control-label" for="check-all">Check all</label>
                    </div>

                    <div class="d-inline-block ml-3">
                        <select class="selectpicker" title="Bulk actions" data-style="btn-sm" data-width="auto">
                            <option>Delete all</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 text-end text-right my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
                    <span>Sort by:</span>
                    <div class="d-inline-block mx-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm icon-right">Date <i class="mdi mdi-chevron-up text-muted"></i></button>
                    </div>

                    <div class="d-inline-block">
                        <button type="button" class="btn btn-outline-secondary btn-sm icon-right">Title <i class="mdi mdi-chevron-down text-muted"></i></button>
                    </div>
                </div>
            </div>

            <div class="muted-cards">
                <div class="card card-product-holder mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center" style="max-width: 40px;">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mx-1">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link text-muted px-0 js-move"><i class="mdi mdi-cursor-move"></i></button>
                            </div>

                            <div class="col item-image">
                                <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
                                    <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title="Продукт"></i>
                                </div>
                                <div class="img-circle-holder border-radius-0 border-0">
                                    <img src="https://cdn.vox-cdn.com/thumbor/uejBZCud-JlRsCn5w26ltmfdGFs=/0x0:675x450/1200x800/filters:focal(284x171:392x279)/cdn.vox-cdn.com/uploads/chorus_image/image/66466293/Untitled_3.0.jpg" />
                                </div>
                            </div>

                            <div class="col item-title">
                                <h5 class="text-dark text-break-line-1 mb-0">Modern Golden Watch</h5>
                                <a href="#" class="text-muted">Category 1,</a> <a href="#" class="text-muted">Category 2</a>
                                <br />
                                <small class="text-muted">http://localhost/microweber/dave-wool-beanie</small>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="#" class="btn btn-outline-success btn-sm">Live edit</a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">Delete</a>
                                </div>
                            </div>

                            <div class="col item-author"><span class="text-muted">Admin</span></div>
                            <div class="col item-comments" style="max-width: 100px;"><span class="text-muted"><i class="mdi mdi-comment mdi-18px"></i> <span class="float-right mx-2">0</span></span></div>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center" style="max-width: 40px;">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mx-1">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link text-muted px-0 js-move"><i class="mdi mdi-cursor-move"></i></button>
                            </div>

                            <div class="col" style="min-width: 120px;">
                                <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
                                    <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title="Продукт"></i>
                                </div>
                                <div class="img-circle-holder border-radius-0 border-0">
                                    <img src="https://cdn.vox-cdn.com/thumbor/uejBZCud-JlRsCn5w26ltmfdGFs=/0x0:675x450/1200x800/filters:focal(284x171:392x279)/cdn.vox-cdn.com/uploads/chorus_image/image/66466293/Untitled_3.0.jpg" />
                                </div>
                            </div>

                            <div class="col" style="min-width: 270px;">
                                <h5 class="text-dark text-break-line-1 mb-0">Modern Golden Watch</h5>
                                <a href="#" class="text-muted">Category 1,</a> <a href="#" class="text-muted">Category 2</a>
                                <small class="text-muted">http://localhost/microweber/dave-wool-beanie</small>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="#" class="btn btn-outline-success btn-sm">Live edit</a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">Delete</a>
                                </div>
                            </div>

                            <div class="col text-end text-right"><span class="text-muted">Admin</span></div>
                            <div class="col text-end text-right" style="max-width: 100px;"><span class="text-muted"><i class="mdi mdi-comment mdi-18px"></i> <span class="float-right mx-2">0</span></span></div>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col text-center" style="max-width: 40px;">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mx-1">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link text-muted px-0 js-move"><i class="mdi mdi-cursor-move"></i></button>
                            </div>

                            <div class="col" style="min-width: 120px;">
                                <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
                                    <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title="Продукт"></i>
                                </div>
                                <div class="img-circle-holder border-radius-0 border-0">
                                    <i class="mdi mdi-shopping mdi-64px text-muted"></i>
                                </div>
                            </div>

                            <div class="col" style="min-width: 270px;">
                                <h5 class="text-dark text-break-line-1 mb-0">Modern Golden Watch</h5>
                                <a href="#" class="text-muted">Category 1,</a> <a href="#" class="text-muted">Category 2</a>
                                <small class="text-muted">http://localhost/microweber/dave-wool-beanie</small>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="#" class="btn btn-outline-success btn-sm">Live edit</a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">Delete</a>
                                </div>
                            </div>

                            <div class="col text-end text-right"><span class="text-muted">Admin</span></div>
                            <div class="col text-end text-right" style="max-width: 100px;"><span class="text-muted"><i class="mdi mdi-comment mdi-18px"></i> <span class="float-right mx-2">0</span></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-pagination">
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">5</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </div>



    <div class="row copyright mt-3">
        <div class="col-12">
            <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
        </div>
    </div>
</main>


<?php include('partials/footer.php'); ?>
