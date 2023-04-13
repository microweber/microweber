<div class="page-header d-print-none">
    <div class="row g-2 align-items-center px-5">
        <div class="col">
            <?php
            if (user_can_access('module.content.edit')):
                ?>
            <li class="mx-1 d-none d-md-block">
                <button type="button" class="btn btn-primary  bg-white border-0 admin-toolbar-buttons"
                        data-bs-toggle="modal"  data-bs-target="#modal-add-new-admin" aria-expanded="false">
                    <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/add-new-button.svg" alt="">
                </button>


{{--                <div class="dropdown-menu ">--}}
{{--                        <?php $custom_view = url_param('view'); ?>--}}
{{--                        <?php $custom_action = url_param('action'); ?>--}}
{{--                        <?php event_trigger('content.create.menu'); ?>--}}
{{--                        <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>--}}
{{--                        <?php if (!empty($create_content_menu)): ?>--}}
{{--                        <?php foreach ($create_content_menu as $type => $item): ?>--}}
{{--                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>--}}
{{--                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>--}}
{{--                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>--}}
{{--                        <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>--}}
{{--                        <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>--}}
{{--                        <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>--}}
{{--                        <?php--}}
{{--                        $base_url = route('admin.content.create');--}}
{{--                        if (Route::has('admin.' . $item['content_type'] . '.create')) {--}}
{{--                            $base_url = route('admin.' . $item['content_type'] . '.create');--}}
{{--                        }--}}
{{--                        ?>--}}
{{--                    <a class="dropdown-item" href="<?php print $base_url; ?>"><span--}}
{{--                            class="<?php print $class; ?>"></span> <?php print $title; ?></a>--}}
{{--                    <?php endforeach; ?>--}}
{{--                    <?php endif; ?>--}}
{{--                </div>--}}
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


<div class="modal modal-blur fade hide" id="modal-add-new-admin" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-title settings-title-inside">
                    Add New
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-wrap align-items-center justify-content-between">
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

                <div class="col-lg-3 text-center admin-add-new-modal-buttons">

                   <a href="<?php print $base_url; ?>" class="">

                       <?php if ($item['content_type'] == 'page') { ?>
                            <div>
                                <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-page.svg" alt="">
                            </div>
                      <?php }?>

                      <?php if ($item['content_type'] == 'post') { ?>
                            <div>
                                <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-post.svg" alt="">
                            </div>
                       <?php }?>

                       <?php if ($item['content_type'] == 'category') { ?>
                            <div>
                                <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-category.svg" alt="">
                            </div>
                      <?php }?>

                       <?php if ($item['content_type'] == 'product') { ?>
                            <div>
                                <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-product.svg" alt="">

                            </div>
                       <?php }?>

                       <a class="text-dark" href="<?php print $base_url; ?>">
                            <div class="my-2">
                                <span class="<?php print $class; ?>"></span> <?php print $title; ?>

                            </div>
                       </a>
                   </a>
                </div>

                       <?php endforeach; ?>
                       <?php endif; ?>
            </div>
        </div>
    </div>
</div>
