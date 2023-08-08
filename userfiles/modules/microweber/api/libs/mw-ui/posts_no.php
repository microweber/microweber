<?php include('partials/header.php'); ?>


<div class="tree">
    DURVO
</div>

<script>
    $(document).ready(function () {
//        $('body > .main').addClass('show-sidebar-tree');
    });
</script>

<main>
    <div class="card-body mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>Shop</strong></h5>
            <div class="d-none">
                <div class="input-group mb-0 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm" aria-label="Search" placeholder="Search">
                </div>
            </div>
        </div>

        <div class=" ">
            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('assets/img/no_content.svg'); ">
                        <h4>You don’t have any posts yet</h4>
                        <p>Create your first post right now.<br />
                            You are able to do that in very easy way!</p>
                        <br />
                        <a href="#" class="btn btn-primary btn-rounded">Create a Product</a>
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
