<div class="page-header d-print-none">
    <div class="row g-2 align-items-center px-5">
        <div class="col">
            <?php
            if (user_can_access('module.content.edit')):
                ?>
            <li class="mx-1 d-none d-md-block">
                <button type="button" class="btn btn-primary  bg-white border-0 admin-toolbar-buttons"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/add-new-button.svg" alt="">
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
                <li>
                    <a href="<?php print $past_page ?>?editmode=y"
                       class="btn btn-primary bg-white border-0 go-live-edit-href-set admin-toolbar-buttons">
                        <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
                        <span class="text-dark ms-2" style="font-size: 14px; font-weight: bold;">EDIT</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php event_trigger('mw.admin.header.toolbar.ul'); ?>
            </ul>
        </div>
    </div>
</div>
