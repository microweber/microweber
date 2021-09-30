<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <script>
        $(document).ready(function () {


        })
    </script>

    <?php
    $isFullpageCached = false;
    ?>

    <div class="card-body border-0">
        <div class="text-center <?php if ($isFullpageCached): ?>card-success<?php else: ?> card-danger <?php endif; ?>">
            <div class="card-body p-5">
                <i class="mdi mdi-rocket" style="font-size:62px;color:#4592ff;"></i>
                <h4>Increase speed of your website</h4>
                <h5>with Fullpage Cache Module</h5>
                <br><br>
                <div class="d-flex justify-content-center ">
                    <p>The 3210 pages not cached.</p>
                </div>
                <?php if ($isFullpageCached) { ?>
                <br> <br>
                <h1 class="text-success"><i class="mw-standalone-icons mdi mdi-check-circle-outline"></i>
                    <h4><h5 class="text-success font-weight-bold"> Fullpage Cached</h5></h4>
                    <?php
                    } else { ?>
                        <br> <br>
                        <h1 class="text-danger"><i class="mw-standalone-icons mdi mdi-close-circle-outline"></i></h1>
                        <h5 class="text-danger font-weight-bold"> Not cached</h5><br/>
                    <?php } ?>
                    <br><br>


            </div>
        </div>
    </div>
