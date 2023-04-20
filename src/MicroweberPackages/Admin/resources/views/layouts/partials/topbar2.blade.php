<div class="page-header d-print-none">
    <div class="row g-2 align-items-center px-5">
        <div class="col">
            <li class="mx-1 d-none d-md-block">
                @yield('topbar2-links-left', \View::make('admin::layouts.partials.topbar2-links-left-default'))
            </li>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <?php
            event_trigger('mw.admin.header.toolbar'); ?>

            <ul class="nav">
                @yield('topbar2-links-right', \View::make('admin::layouts.partials.topbar2-links-right-default'))
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
            <div class="modal-body">
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

                <a href="<?php print $base_url; ?> " class="col-12 text-start d-flex align-items-center flex-wrap admin-add-new-modal-buttons me-auto">



                    <?php if ($item['content_type'] == 'page') { ?>
                    <div class="col-xl-2 col-12 modal-add-new-buttons-img">
                        <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-page.svg" alt="">
                    </div>

                    <div class="col-xl-10 ps-xl-3">
                        <p class="text-dark font-weight-bold mb-0 modal-add-new-buttons-p">
                            Create a new page to your website or online store, choose from pre-pared page designs
                        </p>
                    </div>
                    <?php }?>

                    <?php if ($item['content_type'] == 'post') { ?>
                    <div class="col-xl-2 col-12 modal-add-new-buttons-img">
                        <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-post.svg" alt="">
                    </div>
                    <div class="col-xl-10 ps-xl-3">
                        <p class="text-dark font-weight-bold mb-0 modal-add-new-buttons-p">
                            Add new post to your blog page, linked to category of main page on your website
                        </p>
                    </div>
                    <?php }?>

                    <?php if ($item['content_type'] == 'category') { ?>
                    <div class="col-xl-2 col-12 modal-add-new-buttons-img">
                        <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-category.svg" alt="">
                    </div>
                    <div class="col-xl-10 ps-xl-3">
                        <p class="text-dark font-weight-bold mb-0 modal-add-new-buttons-p">
                            Add new category and organize your blog posts or items from the shop in the right way
                        </p>
                    </div>
                    <?php }?>

                    <?php if ($item['content_type'] == 'product') { ?>
                    <div class="col-xl-2 col-12 modal-add-new-buttons-img">
                        <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-product.svg" alt="">

                    </div>
                    <div class="col-xl-10 ps-xl-3">
                        <p class="text-dark font-weight-bold mb-0 modal-add-new-buttons-p">
                            Add new category and organize your blog posts or items from the shop in the right way
                        </p>
                    </div>
                    <?php }?>

                    {{--                       <span class="text-dark">--}}
                    {{--                            <div class="my-2">--}}
                    {{--                                <span class="<?php print $class; ?>"></span> <?php print $title; ?>--}}
                    {{--                            </div>--}}
                    {{--                       </span>--}}
                </a>

                <?php endforeach; ?>

                <div class="col-12 ps-4 mt-4 modal-add-new-buttons-p">
                    <a href=""> Edit current</a></p>

                </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
