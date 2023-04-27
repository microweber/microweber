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
                </div>
                <div class="col-xxl-6 col-xl-8 col-lg-10 col-12 pe-3">
                    <?php event_trigger('mw.admin.dashboard.content.2'); ?>
                    <?php event_trigger('mw.admin.dashboard.content.1'); ?>
                </div>
            </div>


    <?php event_trigger('mw.admin.dashboard.main'); ?>

</div>
