<div class="page-header d-print-none">
    <div class="row g-2 align-items-center px-5">
        <div class="col">
            <?php
            if (user_can_access('module.content.edit')):
                ?>
            <li class="mx-1 d-none d-md-block">
                <button type="button" class="btn btn-outline-success btn-rounded btn-sm-only-icon "
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-plus"></i>
                    <span class="d-none d-md-block"><?php _e("Add New"); ?></span>
                </button>
                <div class="dropdown-menu ">
                        <?php $custom_view = url_param('view'); ?>
                        <?php $custom_action = url_param('action'); ?>
                        <?php event_trigger('content.create.menu'); ?>
                        <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                        <?php if (!empty($create_content_menu)): ?>
                        <?php foreach ($create_content_menu as $type => $item): ?>
                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                        <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                        <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                        <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>
                        <?php
                        $base_url = route('admin.content.create');
                        if (Route::has('admin.' . $item['content_type'] . '.create')) {
                            $base_url = route('admin.' . $item['content_type'] . '.create');
                        }
                        ?>
                    <a class="dropdown-item" href="<?php print $base_url; ?>"><span
                            class="<?php print $class; ?>"></span> <?php print $title; ?></a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </li>
            <?php endif; ?>

        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <?php
            $past_page = site_url();

            event_trigger('mw.admin.header.toolbar'); ?>

            <ul class="nav">
                <?php if (user_can_access('module.content.edit')): ?>
                <li class="mx-1">
                    <a href="<?php print $past_page ?>?editmode=n"
                       class="btn btn-outline-success btn-rounded btn-sm-only-icon go-live-edit-href-set go-live-edit-href-set-view">
                        <i class="mdi mdi-earth"></i><span class="d-none d-md-block ml-1"><?php _e("Website"); ?></span>
                    </a>
                </li>
                <li class="mx-1">
                    <a href="<?php print $past_page ?>?editmode=y"
                       class="btn btn-primary btn-rounded btn-sm-only-icon go-live-edit-href-set">
                        <i class="mdi mdi-eye-outline"></i><span
                            class="d-none d-md-block ml-1"><?php _e("Live Edit"); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php event_trigger('mw.admin.header.toolbar.ul'); ?>
            </ul>
        </div>
    </div>
</div>
