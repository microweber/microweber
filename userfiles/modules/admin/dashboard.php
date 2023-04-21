<div class="col-xxl-8 col-xl-10 col-12 mx-auto">

    <div class="dashboard-title-container ">
            <h1 class="dashboard-welcome-title">Welcome back, <?php print user_name(); ?></h1>

        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <p class="dashboard-welcome-p">Here's what's happening</p>
            </div>
            <div>
                <p class="dashboard-welcome-p">Last 30 days</p>
            </div>
        </div>
    </div>

    <?php event_trigger('mw.admin.dashboard.start'); ?>

            <?php event_trigger('mw.admin.dashboard.content.before'); ?>
            <?php event_trigger('mw.admin.dashboard.content'); ?>
        <div class="row">

            <div class="col-xxl-6 col-xl-8 col-lg-10 col-12 pe-3">
                <?php event_trigger('mw.admin.dashboard.content.3'); ?>

                <div class="card mb-4 dashboard-admin-cards">
                    <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="dashboard-icons-wrapper wrapper-icon-sales">
                                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/admin-dashboard-sales.png" alt="messages">
                            </div>


                            <div class="row">
                                <p> <?php _e("Sales") ?></p>
                                <h5 class="dashboard-numbers">
                                    $10
                                </h5>
                            </div>
                        </div>


                        <div>
                            <a href="<?php print admin_url('module/view?type=contact_form'); ?>" class="btn btn-link text-dark"><?php _e('View'); ?></a>
                        </div>

                    </div>
                </div>
            </div>





            <div class="col-xxl-6 col-xl-8 col-lg-10 col-12 pe-3">
                <?php event_trigger('mw.admin.dashboard.content.2'); ?>
                <?php event_trigger('mw.admin.dashboard.content.1'); ?>
            </div>


        </div>



    <div class="card  mb-5" style="height: 200px;">
        <div class="card-body">
          <div class="row">

          </div>
        </div>
    </div>

    <?php event_trigger('mw.admin.dashboard.main'); ?>

</div>
