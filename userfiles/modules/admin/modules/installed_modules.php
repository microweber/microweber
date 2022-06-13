<script type="text/javascript">
    mw.require('forms.js');
</script>

<script type="text/javascript">
    $(document).ready(function () {

        mw.dropdown();
        mw.$('#modules_categories_tree_<?php print $params['id']; ?> .fixed-side-column-container .well').prepend('<ul class="category_tree"><li><a href="#?category=0" data-category-id="0" onclick="mw.url.windowHashParam(\'category\', 0);return false;"><?php _e("All"); ?></a></li></ul>');
        mw.$('#modules_categories_tree_<?php print $params['id']; ?> li a').each(function () {
            var el = this;
            var id = el.attributes['data-category-id'].nodeValue;
            el.href = '#?category=' + id;
            el.className += ' cat-' + id;
            el.setAttribute('onclick', 'mw.url.windowHashParam("category",' + id + ');return false;');
        });

        var h = mw.hash();

        if (h === '' || h === '#' || h === '#?') {
            _modulesSort();
        } else {
            var hash = mw.url.getHashParams(h);
            try {
                document.querySelector(".modules-index-bar input[value='" + hash.ui + "']").checked = true;
            } catch (e) {
            }
        }

        mw.$("select.js-modules-sort-types").on('change', function () {
            var val = $(this).val();
            mw.url.windowHashParam('ui', val);
        });

        $("select.js-modules-sort-status").on('change', function () {
            var val = $(this).val();
            mw.url.windowHashParam('installed', val);
        });
    });

    <?php if (config('microweber.allow_php_files_upload')): ?>
    function mw_upload_module() {
        mwUploadModuleDialog = mw.dialog({
            content: '<div id="mw_admin_upload_module_modal_content"></div>',
            title: 'Upload Module',
            id: 'mw_admin_upload_module_modal'
        });
        var params = {};
        mw.load_module('admin/modules/upload', '#mw_admin_upload_module_modal_content', function () {
            mwUploadModuleDialog.center();
        }, params);
    }
    <?php endif; ?>

    function mw_reload_all_modules() {
        mw_reload_all_elements()
        mw.notification.success('Reloading...', 3000)
        mw.tools.loading(true);

        mw.$('#modules_admin_<?php print $params['id']; ?>').attr('reload_modules', 1);
        mw.$('#modules_admin_<?php print $params['id']; ?>').attr('cleanup_db', 1);
        $(".reload-module-btn").addClass('reloading')
        mw.load_module('admin/modules/manage', '#modules_admin_<?php print $params['id']; ?>', function () {

            mw.notification.success('Modules have been reloaded', 5000)
            mw.$('#modules_admin_<?php print $params['id']; ?>').removeAttr('cleanup_db');
            $(".reload-module-btn").removeClass('reloading');
            mw.tools.loading(false);
        });
    }


    function mw_reload_all_elements() {
        mw.tools.loading(true);
        mw.$('#reload_elements_empty_holder').attr('reload_modules', 1);
        mw.$('#reload_elements_empty_holder').attr('cleanup_db', 1);
        mw.load_module('admin/modules/elements', '#reload_elements_empty_holder', function () {
            mw.tools.loading(false);
        });
    }


    _modulesSort = function () {

        var hash = mw.url.getHashParams(window.location.hash);

        //hash.ui === undefined ? mw.url.windowHashParam('ui', 'admin') : '' ;
        // hash.category === undefined ? mw.url.windowHashParam('category', '0') : '' ;

        var attrs = mw.url.getHashParams(window.location.hash);
        var holder = mw.$('#modules_admin_<?php print $params['id']; ?>');

        var arr = ['data-show-ui', 'data-search-keyword', 'data-category', 'data-installed', 'install_new'], i = 0, l = arr.length;

        var sync = ['ui', 'search', 'category', 'installed', 'install_new'];

        for (; i < l; i++) {
            holder.removeAttr(arr[i]);
        }

        if (hash.ui === undefined) {
            holder.attr('data-show-ui', 'admin');
        }


        for (var x in attrs) {
            if (x === 'category' && (attrs[x] === '0' || attrs[x] === undefined)) continue;
            holder.attr(arr[sync.indexOf(x)], attrs[x]);
        }
        mw.load_module('admin/modules/manage', '#modules_admin_<?php print $params['id']; ?>', function () {
            $('#module_keyword').removeClass('loading');


            var el = $("#modules_admin_<?php print $params['id']; ?> .mw-modules-admin");
            // $( "#modules_admin_<?php print $params['id']; ?> .mw-modules-admin" ).sortable('destroy');
            el.sortable({
                handle: ".mw_admin_modules_sortable_handle",
                items: "li",
                axis: 'y',
                update: function () {
                    var serial = el.sortable('serialize');
                    $.ajax({
                        url: mw.settings.api_url + 'module/reorder_modules',
                        type: "post",
                        data: serial
                    });
                }
            });


        });
    }

    $(document).ready(function () {
        mw.on.hashParam('ui', _modulesSort);
        mw.on.hashParam('installed', _modulesSort);

        mw.on.hashParam('search', function (value) {
            _modulesSort();
            var field = document.getElementById('module_keyword');

            if (!field.focused) {
                field.value = value;
            }
        });

        mw.on.hashParam('install_new', function () {
            _modulesSort();
        });

        mw.on.hashParam('category', function (value) {
            _modulesSort();
            mw.$("#mw_index_modules a.active").removeClass('active');
            mw.$("#mw_index_modules .cat-" + value).addClass('active');
        });
        mw.on.hashParam('installed', function () {
            _modulesSort();
        });

        $('#module_keyword').on('change', function () {
            if ($(this).val() == '') {
                mw.url.windowHashParam('search', this.value)
            }
        });

        $('#module_keyword').keypress(function (e) {
//            e.preventDefault();
            var key = e.which;
            if (key == 13) {
                mw.url.windowHashParam('search', $('#module_keyword').val());
                return false;
            }
        });

        $('.js-search-keyword').on('click', function (e) {
            e.preventDefault();
            mw.url.windowHashParam('search', $('#module_keyword').val())
        })
    });

    $(document).ready(function () {
        _modulesSort();
        mw.on.hashParam('market', function (value) {
            if (value !== false) {
                mw.$('html').addClass('market-init');
                mw.load_module('admin/modules/market', '#modules_market_<?php print $params['id']; ?>');
                /*        $("#modules_admin_<?php print $params['id']; ?>").hide();
                 $("#modules_market_<?php print $params['id']; ?>").show();
                 $(".modules-index-bar").hide();*/
            } else {
                mw.$('html').removeClass('market-init');
            }
        })


    });


</script>
<?php
$show_by_categories = false;
if(isset($_GET['show_modules_by_categories']) and intval($_GET['show_modules_by_categories']) != 0){
    $show_by_categories = true;
}
?>
<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-header px-0">
        <h5>
            <i class="mdi mdi-view-grid-plus text-primary mr-3"></i> <strong><?php _e("Modules"); ?></strong>
        </h5>
        <div>

        </div>
    </div>

    <div class="card-body pt-3 px-0">
        <script>
            $(document).ready(function () {
                $('.js-show-filter').on('click', function () {
                    $(this).toggleClass('active');
                    if ($(this).hasClass('active')) {
                        $(this).find('i').removeClass('mdi-filter-outline').addClass('mdi-close-thick');
                    } else {
                        $(this).find('i').removeClass('mdi-close-thick').addClass('mdi-filter-outline');
                    }
                });
            });
        </script>

        <div class="d-lg-block d-xl-flex text-center align-items-center justify-content-between mt-3 mb-4">
            <div class="mb-3">
                <form class="d-flex justify-content-center">
                    <div class="form-group mb-0">
                        <div class="input-group mb-0 prepend-transparent">
                            <div class="input-group-prepend bg-white">
                                <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                            </div>

                            <input value="" type="search" class="form-control" name="module_keyword" id="module_keyword" placeholder='<?php _e("Search for modules"); ?>' autocomplete="off">
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary ml-2 js-search-keyword"><?php _e("Search"); ?></button>
                </form>
            </div>

            <div class="mb-3">

                <?php if ($show_by_categories): ?>
                    <a href="?show_modules_by_categories=0" class="btn  btn-primary btn-md  btn-icon" title="Do not sort by category"><i class="mdi mdi-view-list"></i> </a>
                <?php else: ?>
                    <a href="?show_modules_by_categories=1" class="btn btn-outline-primary btn-md  btn-icon" title="Sort by category"><i class="mdi mdi-view-list"></i> </a>
                <?php endif; ?>


                <a href="#" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-bs-toggle="collapse" data-target="#show-filter"><i class="mdi mdi-filter-outline"></i> <?php _e("Filter"); ?></a>

                <?php if (config('microweber.allow_php_files_upload')): ?>
                <a href="javascript:;" onclick="mw_upload_module()" class="btn btn-outline-primary icon-left">
                    <i class="mdi mdi-upload icon-left"></i> <?php _e("Upload module"); ?>
                </a>
                <?php endif;?>

                <?php if (user_can_access('module.modules.edit')): ?>
                    <a href="javascript:;" onclick="mw_reload_all_modules()" class="btn btn-primary reload-module-btn icon-left"><i class="mdi mdi-refresh icon-left"></i> <?php _e("Reload modules"); ?></a>
                <?php endif; ?>

            </div>
        </div>


        <div class="collapse" id="show-filter">
            <div class="bg-primary-opacity-1 rounded p-4 mb-4">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <div>
                            <label class="d-block mb-2"><?php _e("Type"); ?></label>

                            <select class="selectpicker js-modules-sort-types" data-width="100%">
                                <option value="live_edit"><?php _e("Live edit modules"); ?></option>
                                <option value="admin" selected><?php _e("Admin modules"); ?></option>
                                <option value="advanced"><?php _e("All modules"); ?></option>
                                <option value="elements"><?php _e("Elements"); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label class="d-block mb-2"><?php _e("Status"); ?></label>

                            <select class="selectpicker js-modules-sort-status" data-width="100%">
                                <option value="1"><?php _e("Installed"); ?></option>
                                <option value="0"><?php _e("Uninstalled"); ?></option>
                                <!--                                <option value="2">--><?php //_e("All"); ?><!--</option>-->
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mw-dropdown pull-left nested-dropdown" style="display:none;"><span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val mw-dropdown-button"><?php _e("Categories"); ?></span>
                <div class="mw-dropdown-content">
                    <module type="categories" data-for="modules" id="modules_admin_categories_<?php print $params['id']; ?>"/>
                </div>
            </div>
        </div>

        <div class="mw-modules-admin-holder">


            <?php if ($show_by_categories): ?>
            <div show_modules_by_categories="1" id="modules_admin_<?php print $params['id']; ?>"></div>
            <?php else: ?>
             <div id="modules_admin_<?php print $params['id']; ?>"></div>
            <?php endif; ?>
            <div id="modules_market_<?php print $params['id']; ?>"></div>
        </div>
    </div>
</div>

<div id="reload_elements_empty_holder" style="display: none"></div>
