<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<module type="admin/modules/info"/>

<?php
if (!user_can_access('module.marketplace.index')) {
    return;
}
?>

<script>mw.require('admin_package_manager.js');</script>

<?php
$is_update_mode = false;
$core_update = false;

//$search_packages_params = array();
$search_packages_params['cache'] = true;

//$search_packages_params2 = $search_packages_params;
//$search_packages_params2['return_local_packages'] = true;
//$search_packages_params2['return_only_updates'] = true;

if (isset($params['show_only_updates']) and $params['show_only_updates']) {
    $search_packages_params['return_local_packages'] = true;
    $search_packages_params['return_only_updates'] = true;
    $is_update_mode = true;
}

$search_packages = mw()->update->composer_search_packages($search_packages_params);
//$search_packages_update = mw()->update->composer_search_packages($search_packages_params2);
//$search_packages = mw()->update->composer_search_packages();


$packages_by_type = array();
$packages_by_type_with_update = array();

if ($search_packages and is_array($search_packages)) {
    foreach ($search_packages as $key => $item) {
        $package_has_update = false;
        //if ($item['type'] != 'microweber-core-update') {
        if (isset($item['has_update']) and $item['has_update']) {
            $package_has_update = true;
        }

        if ($package_has_update) {
            $package_has_update_key = $item['type'];
            if (!isset($packages_by_type_with_update[$package_has_update_key])) {
                $packages_by_type_with_update[$package_has_update_key] = array();
            }
            $packages_by_type_with_update[$package_has_update_key][] = $item;
        }
        //}
        if ($item['type'] != 'microweber-core-update') {
            if (!isset($packages_by_type[$item['type']])) {
                $packages_by_type[$item['type']] = array();
            }
            $packages_by_type[$item['type']][] = $item;
        }
    }
}

if ($is_update_mode and isset($packages_by_type_with_update['microweber-core-update']) and !empty($packages_by_type_with_update['microweber-core-update'])) {
    $core_update = $packages_by_type_with_update['microweber-core-update'];
    unset($packages_by_type_with_update['microweber-core-update']);
    //$packages_by_type_with_update['microweber-core-update'] = array();
    //$packages_by_type_with_update['microweber-core-update'][] = $core_update;
}

$packages_by_type_all = array_merge($packages_by_type, $packages_by_type_with_update);
// dd($packages_by_type_all,$packages_by_type_with_update);
?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header d-flex justify-content-between">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <?php if ($is_update_mode) { ?>
                <i class="mdi mdi-update text-primary mr-3"></i> <strong><?php _e("Updates"); ?></strong>
            <?php } else { ?>
                <i class="mdi mdi-fruit-cherries text-primary mr-3"></i> <strong><?php _e("Marketplace"); ?></strong>
            <?php } ?>
        </h5>

        <div clsas="d-flex align-items-center">
            <div class="d-inline-block dropleft">
                    <button class="btn btn-outline-primary btn-sm btn-icon" type="button" id="moreSettings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-cog"></i></button>
                    <div class="dropdown-menu" aria-labelledby="moreSettings">
                        <?php if ($is_update_mode) { ?>
                            <a href="<?php print admin_url() ?>view:packages" class="dropdown-item"><?php _e("Show all packages"); ?></a>
                        <?php } else { ?>
                            <a href="<?php print admin_url() ?>view:settings#option_group=updates" class="dropdown-item"><?php _e("Show updates"); ?></a>
                        <?php } ?>
                        <a href="javascript:;" class="dropdown-item" onclick="mw.admin.admin_package_manager.reload_packages_list();"><?php _e("Reload packages"); ?></a>
                        <a href="javascript:;" class="dropdown-item" onclick="mw.admin.admin_package_manager.show_licenses_modal ();"><?php _e("Licenses"); ?></a>
                    </div>
            </div>

            <div class="d-inline-block">
                <div class="form-inline">
                    <div class="input-group mb-0 prepend-transparent mx-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                        </div>

                        <input type="text" class="form-control form-control-sm" name="module_keyword" style="width: 100px;" value="" placeholder="<?php _e("Search"); ?>" onkeyup="mw.url.windowHashParam('search',this.value)">
                    </div>

                    <button type="button" class="btn btn-primary btn-sm btn-icon" onclick="mw.url.windowHashParam('search',$(this).prev().find('input').val())"><i class="mdi mdi-magnify"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body pt-3">

        <script>

            $(document).ready(function () {
                mw.on.hashParam('search', function (pval) {
                    if (pval === false) return false;

                    var search_kw = pval;
                    var items = document.querySelectorAll('.text-dark');
                    var foundlen = 0;

                    mw.tools.search(search_kw, items, function (found) {
                        if (found) {
                            foundlen++;
                            $(this).parents('.js-package-install-content').show();
                        } else {
                            $(this).parents('.js-package-install-content').hide();
                        }
                    });
                });
            },$('.module-packages'));

            $(document).ready(function () {
                mw.tabs({
                    nav: '#mw-packages-browser-nav-tabs-nav .mw-ui-navigation a.tablink',
                    tabs: '#mw-packages-browser-nav-tabs-nav .tab'
                    //linkable: 'section'
                });
            });
        </script>

        <style>
            .package-image {
                display: block;
                width: 150px;
                height: 150px;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center top;
                margin: 10px auto 10px auto !important;
                overflow: hidden;
            }

            .package-image.package-microweber-template {
                width: 100%;
                height: 475px;
                cursor: -webkit-grab;
                cursor: -moz-grab;
                cursor: grab;
            }

            .package-image img {
                width: 100%;
            }

            html.has-scroll-hover .package-image {
                cursor: grabbing;
            }

            .mw-scroll-hover {
                width: 300px;
                height: 400px;
                overflow: hidden;
                cursor: grab;
            }

            .package-microweber-module {
                width: 100%;
                background-size: contain;
            }

            .package-ext-link {
                /* These are technically the same, but use both */
                overflow-wrap: break-word;
                word-wrap: break-word;

                -ms-word-break: break-all;
                /* This is the dangerous one in WebKit, as it breaks things wherever */
                word-break: break-all;
                /* Instead use this non-standard one: */
                word-break: break-word;

                /* Adds a hyphen where the word breaks, if supported (No Blink) */
                -ms-hyphens: auto;
                -moz-hyphens: auto;
                -webkit-hyphens: auto;
                hyphens: auto;
                overflow: hidden;

            }
            .package-item-footer {
                padding: 12px 0 0 0;
            }
        </style>

        <script>
            $(document).ready(function () {
                $('.package-microweber-template').each(function () {
                    $(this)
                        .on('mouseenter', function () {
                            var el = $(this);
                            el.stop();
                            this._hoverInterval = setInterval(function (node) {
                                node[0].scrollTop = node[0].scrollTop + 4
                            }, 10, el);
                        })
                        .on('mouseleave', function () {
                            clearInterval(this._hoverInterval);
                            if (!mw.scrollHoverPageY) {
                                $(this).stop().animate({scrollTop: 0}, 200);
                            }
                        })
                    /*.on('mousedown touchstart', function (e) {
                     e.preventDefault();
                     clearInterval(this._hoverInterval);
                     $(this).stop();
                     mw.scrollHover$ = $(this);
                     $(document.documentElement).addClass('has-scroll-hover')
                     })*/
                });

                $(document).on('mouseup touchend', function () {
                    mw.scrollHover$ = null;
                    mw.scrollHoverY = null;
                    mw.scrollHoverPageY = null;
                    $(document.documentElement).removeClass('has-scroll-hover')
                }).on('mousemove touchmove', function (e) {
                    if (mw.scrollHover$) {
                        var state;
                        state = (mw.scrollHoverPageY > e.pageY ? mw.scrollHoverPageY - e.pageY : -(e.pageY - mw.scrollHoverPageY));
                        if (isNaN(state)) {
                            state = 0;
                        }
                        if (Math.abs(state) > 20) {
                            state = state < 0 ? -2 : 2;
                        }
                        mw.scrollHover$[0].scrollTop = mw.scrollHover$[0].scrollTop + state;

                        mw.scrollHoverPageY = e.pageY;
                        mw.scrollHoverY = state;
                    }
                })
            });
        </script>

        <script>
            $(document).ready(function () {
                $('.mw-sel-item-key-install').change(function () {
                    var val = $("option:selected", this).val();
                    var vkey = $(this).data('vkey');
                    var holder = mw.tools.firstParentOrCurrentWithClass(this, 'js-package-install-content');
                    $('.js-package-install-btn', holder).html("Install " + val);
                    $('.js-package-install-btn', holder).data('vkey', val);
                    $('.js-package-install-btn', holder).show();
                    $('.js-package-install-btn-help-text', holder).hide();
                });
            });
        </script>
        <script>mw.lib.require('mwui_init');</script>

        <?php if (!$is_update_mode) : ?>
            <p><?php _e('Welcome to the marketplace');?> <?php _e('Here you will find new modules, templates and updates'); ?></p>
        <?php endif; ?>

        <div id="mw-packages-browser-nav-tabs-nav">
            <div class="row">
                <?php if ($core_update) : ?>
                    <?php foreach ($core_update as $pkkey => $pkitems): ?>
                        <?php foreach ($core_update as $key => $item): ?>
                            <div class="col-12 col-md-6 mb-4">
                                <?php
                                $view_file = __DIR__ . '/partials/package_item.php';
                                $view = new \MicroweberPackages\View\View($view_file);
                                $view->assign('item', $item);
                                $view->assign('no_img', true);
                                $view->assign('box_class', 'mw-ui-box-info ');
                                print    $view->display();
                                ?>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="col-12">
                    <?php if ($packages_by_type_all) : ?>
                        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                            <?php if ($packages_by_type_all) : ?>
                                <?php $count = 0; ?>
                                <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                                    <?php
                                    $count++;
                                    $pkkeys = explode('-', $pkkey);
                                    array_shift($pkkeys);
                                    $pkkeys = implode('-', $pkkeys);
                                    $pkkeys = url_title($pkkeys);

                                    ?>
                                    <a class="btn btn-outline-secondary justify-content-center <?php if ($count == 1): ?>active<?php endif; ?>" data-toggle="tab" href="#<?php echo $pkkeys; ?>"><i class="mdi mr-1 <?php if ($pkkeys == 'template'): ?>mdi-pencil-ruler<?php elseif ($pkkeys == 'module'): ?>mdi-view-grid-plus<?php elseif ($pkkeys == 'update'): ?>mdi-flash-outline<?php endif; ?>"></i> <?php print titlelize($pkkeys) ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if ($packages_by_type_with_update): ?>
                                <?php
                                $total = 0;
                                $items = '';
                                foreach ($packages_by_type_with_update as $pkkey => $pkitems):
                                    $pkkeys = explode('-', $pkkey);
                                    array_shift($pkkeys);
                                    $pkkeys = implode('-', $pkkeys);

                                    if ($pkkeys == 'core-update') {
                                        $pkkeys = 'Version update';
                                    } else {
                                        $pkkeys = $pkkeys . ' updates';
                                    }
                                    $pkkeys = url_title($pkkeys);

                                    $count = count($pkitems);
                                    $total += $count;
                                    $items .= '<a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#' . $pkkeys . '">' . titlelize($pkkeys) . '&nbsp; <sup class="badge badge-danger badge-sm badge-pill ml-2">' . $count . '</sup></a>';
                                endforeach;
                                ?>
                                <?php print $items; ?>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>

                    <?php if ($packages_by_type and !empty($packages_by_type)) : ?>
                        <div class="tab-content py-3">
                            <?php $count = 0; ?>
                            <?php foreach ($packages_by_type as $pkkey => $pkitems): ?>
                                <?php $count++; ?>
                                <?php
                                $pkkeys = explode('-', $pkkey);
                                array_shift($pkkeys);
                                $pkkeys = implode('-', $pkkeys);
                                $pkkeys = url_title($pkkeys);

                                ?>
                                <div class="tab-pane fade <?php if ($count == 1): ?>show active<?php endif; ?>" id="<?php echo $pkkeys; ?>">
                                    <?php if ($pkitems) : ?>
                                        <div class="row">
                                            <?php foreach ($pkitems as $key => $item): ?>
                                                <div class="col-12 col-sm-6 col-md-<?php print $item['type'] === 'microweber-module' ? '6' : '12'; ?> col-lg-<?php print $item['type'] === 'microweber-module' ? '4' : '6'; ?> mb-4 package-col-<?php print $item['type']; ?>">
                                                    <?php
                                                    $view_file = __DIR__ . '/partials/package_item.php';
                                                    $view = new \MicroweberPackages\View\View($view_file);
                                                    $view->assign('item', $item);
                                                    print    $view->display();
                                                    ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                            <?php foreach ($packages_by_type_with_update as $pkkey => $pkitems): ?>
                                <?php
                                $pkkeys = explode('-', $pkkey);
                                array_shift($pkkeys);
                                $pkkeys = implode('-', $pkkeys);
                                $pkkeys = $pkkeys . ' updates';

                                $pkkeys = url_title($pkkeys);
                                ?>
                                <div class="tab-pane fade" id="<?php echo $pkkeys; ?>">
                                    <?php if ($pkitems) : ?>
                                        <div class="row">
                                            <?php foreach ($pkitems as $key => $item): ?>
                                                <?php

                                                $holder_class = 'col-lg-6';
                                                if ($item['type'] == 'microweber-module') {
                                                    $holder_class = 'col-lg-4';
                                                }
                                                if ($item['type'] == 'microweber-core-update') {
                                                    $holder_class = 'col-lg-6';
                                                }
                                                ?>
                                                <div class="col-12 col-sm-6 col-md-12 <?php print $holder_class; ?> mb-4 package-col-<?php print $item['type']; ?>">
                                                    <?php
                                                    $view_file = __DIR__ . '/partials/package_item.php';

                                                    $view = new \MicroweberPackages\View\View($view_file);
                                                    $view->assign('item', $item);

                                                    print    $view->display();
                                                    ?>
                                                </div>

                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>


                    <?php else: ?>
                    <?php if (!$core_update) : ?>
                        <div class="mw-ui-box-content tab">
                            <?php _e("No packages found"); ?>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>mw.require('admin_package_manager.js');</script>
