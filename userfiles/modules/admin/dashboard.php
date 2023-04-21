<div class="col-xl-8 mx-auto">

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
    <div class="d-flex flex-wrap">

        <div class="col-xl-6 pe-2">
            <?php event_trigger('mw.admin.dashboard.content.3'); ?>
        </div>

        <div class="col-xl-6 ps-2">
            <?php event_trigger('mw.admin.dashboard.content.1'); ?>
            <?php event_trigger('mw.admin.dashboard.content.2'); ?>
        </div>


    </div>



    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="mdi mdi-link text-primary mr-3"></i> <strong><?php _e("Quick Links"); ?></strong></h5>
        </div>

        <div class="card-body">
            <div class="row quick-links">
                <?php event_trigger('mw.admin.dashboard.links'); ?>
                <?php $dash_menu = mw()->ui->module('admin.dashboard.menu'); ?>
                <?php if (!empty($dash_menu)): ?>
                    <?php foreach ($dash_menu as $item): ?>
                        <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                        <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                        <?php if ($view == false and $link != false) {
                            $btnurl = $link;
                        } else {
                            $btnurl = admin_url('view:') . $item['view'];
                        } ?>
                        <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                        <?php $text = $item['text']; ?>
                        <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                            <a href="<?php print $btnurl; ?>" class="btn btn-link py-1 px-0"><i class="<?php print $icon; ?> text-dark fs-3 me-2"></i><span><?php print $text; ?></span></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.second'); ?>
                <?php if (!empty($dash_menu)): ?>
                    <?php foreach ($dash_menu as $item): ?>
                        <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                        <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                        <?php if ($view == false and $link != false) {
                            $btnurl = $link;
                        } else {
                            $btnurl = admin_url('view:') . $item['view'];
                        } ?>
                        <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                        <?php $text = $item['text']; ?>
                        <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                            <a href="<?php print $btnurl; ?>" class="btn btn-link py-1 px-0"><i class="<?php print $icon; ?> text-dark fs-3 me-2"></i><span><?php print $text; ?></span></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php event_trigger('mw.admin.dashboard.links2'); ?>
                <?php event_trigger('mw.admin.dashboard.help'); ?>

                <?php
                $showThirdMenu = true;

                $showThirdMenu = mw()->ui->enable_service_links();

                ?>

                <?php if ($showThirdMenu): ?>
                    <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.third'); ?>
                    <?php if (!empty($dash_menu)): ?>
                        <?php foreach ($dash_menu as $item): ?>
                            <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                            <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                            <?php if ($view == false and $link != false) {
                                $btnurl = $link;
                            } else {
                                $btnurl = admin_url('view:') . $item['view'];
                            } ?>
                            <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                            <?php $text = $item['text']; ?>
                            <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                                <a href="<?php print $btnurl; ?>" class="btn btn-link py-1 px-0"><i class="<?php print $icon; ?> text-dark fs-3 me-2"></i><span><?php print $text; ?></span></a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php event_trigger('mw.admin.dashboard.links3'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php event_trigger('mw.admin.dashboard.main'); ?>

</div>
