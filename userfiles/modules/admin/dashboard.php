<?php event_trigger('mw.admin.dashboard.start'); ?>
<div class="mw-ui-col-container mw-admin-dashboard-main">
    <div class="admin-side-content">
        <?php event_trigger('mw.admin.dashboard.content.before'); ?>
        <?php event_trigger('mw.admin.dashboard.content'); ?>


        <?php event_trigger('mw.admin.dashboard.content.1'); ?>

        <?php event_trigger('mw.admin.dashboard.content.2'); ?>

        <?php event_trigger('mw.admin.dashboard.content.3'); ?>


        <div class="card style-1">
            <div class="card-header">
                <h5><i class="mdi mdi-link text-primary mr-3"></i> <strong><?php _e("Quick Links"); ?></strong></h5>
            </div>

            <div class="card-body">




                <div class="row quick-links">
                    <div class="col-12 col-sm-6 col-md-4">
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
                                <a href="<?php print $btnurl; ?>" class="btn btn-link"><span
                                            class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">

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
                                        <a href="<?php print $btnurl; ?>" class="btn btn-link"><span class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php event_trigger('mw.admin.dashboard.links2'); ?>
                                <?php event_trigger('mw.admin.dashboard.help'); ?>

                    </div>

                    <div class="col-12 col-sm-6 col-md-4">

                            <?php if (mw()->ui->enable_service_links): ?>

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
                                            <a href="<?php print $btnurl; ?>" class="btn btn-link"><span class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php event_trigger('mw.admin.dashboard.links3'); ?>

                            <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <?php event_trigger('mw.admin.dashboard.main'); ?>
    </div>
</div>