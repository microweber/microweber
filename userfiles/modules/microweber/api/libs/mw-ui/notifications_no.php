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
            <h5 class="card-title"><i class="mdi mdi-bell text-primary mr-3"></i> <strong>Notifications</strong></h5>
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
                    <div class="no-items-box no-notifications" style="background-image: url('assets/img/no_notifications.svg');">
                        <h4>You don't have any notifications</h4>
                        <p>Here you will be able to see notifications <br /> about orders, comments, email and others.</p>
                        <br />
                        <a href="#" class="btn btn-outline-primary btn-sm icon-left"><i class="mdi mdi-arrow-left"></i> <span class="">Back</span></a>
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
