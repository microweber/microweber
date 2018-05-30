<script>
    $( document ).ready(function() {
        mw.load_module('admin/modules/list','#mw-sidebar-modules-list');
        mw.load_module('admin/modules/list_layouts','#mw-sidebar-layouts-list');
    });




</script>

<div id="modules-and-layouts-sidebar" class="modules-and-layouts-holder mw-normalize-css">
    <a href="javascript:;" title="<?php _e("Open/Close menu"); ?>" data-id="mw-toolbar-show-sidebar-btn"
       class="sidebar-toggler">
        <div class="i-holder">
            <i class="mwi-hamb"></i>
        </div>
    </a>
    <a href="javascript:;" class="close-sidebar-button" title="<?php _e("Close"); ?>"
       data-id="mw-toolbar-show-sidebar-btn"><i class="mwi-close-thin"></i></a>

    <h3 class="tab-title tab-title-0">Add Layout</h3>
    <h3 class="tab-title tab-title-1" style="display: none;">Add Module</h3>
    <h3 class="tab-title tab-title-2" style="display: none;">Settings</h3>

    <div id="mw-modules-layouts-tabsnav">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn tabnav active"><i class="mwi-desktop-plus"></i> Layouts</a>
            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-folder"></i> Modules</a>
            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-cog"></i> Settings</a>
        </div>


        <div id="search-modules-and-layouts" class="">

            <div class="tab-title tab-title-0 layouts">
                <div class="search-wrapper">
                    <label for="search-input">
                        <i class="mw-icon-search" aria-hidden="true"></i>
                    </label>

                    <input onkeyup="mwSidebarSearchItems(this.value, 'layouts')" class="form-control input-lg" placeholder="Search for Layouts"
                           autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1"
                           data-id="mw-sidebar-search-input-for-modules-and-layouts">

                    <a href="javascript:mwSidebarSearchClear('layouts');" class="mw-sidebar-search-clear-x-btn mw-icon-close"
                       aria-hidden="true" style="display: none;"></a>
                </div>

                <p class="mw-search-no-results" style="margin: 35px 0 15px 0; display: none; text-align: center;">No found results</p>
            </div>

            <div class="tab-title tab-title-1 modules" style="display: none;">
                <div class="search-wrapper">
                    <label for="search-input">
                        <i class="mw-icon-search" aria-hidden="true"></i>
                    </label>

                    <input onkeyup="mwSidebarSearchItems(this.value, 'modules')" class="form-control input-lg" placeholder="Search for Modules"
                           autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1"
                           data-id="mw-sidebar-search-input-for-modules-and-layouts">

                    <a href="javascript:mwSidebarSearchClear('modules');" class="mw-sidebar-search-clear-x-btn mw-icon-close"
                       aria-hidden="true" style="display: none;"></a>
                </div>

                <p class="mw-search-no-results" style="margin: 35px 0 15px 0; display: none; text-align: center;">No found results</p>
            </div>
        </div>

        <div class="mw-ui-box mw-scroll-box" id="mw-sidebar-modules-and-layouts-holder">
            <div class="mw-ui-box-content tabitem">

                <div id="mw-sidebar-layouts-list"></div>




            </div>

            <div class="mw-ui-box-content tabitem" style="display: none">
                <div id="mw-sidebar-modules-list"></div>
            </div>

            <div class="mw-ui-box-content tabitem iframe-holder" style="display: none;">
                <?php if (file_exists(TEMPLATE_DIR . 'template_settings.php')) { ?>
                    <iframe class="settings-iframe"
                            src="<?php print api_url() ?>module?id=settings/template&live_edit=true&module_settings=true&type=settings/template&autosize=false"></iframe>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>

        function mwSidebarSearchClear(what) {
            $('[data-id="mw-sidebar-search-input-for-modules-and-layouts"]').val('');
            $('.mw-sidebar-search-clear-x-btn', '.' + what).hide();
            mwSidebarSearchItems('', what);
            $('.mw-search-no-results', '.' + what).hide();
        }

        function mwSidebarSearchItems(value, what) {
            if (what == 'modules') {
                var obj = mw.$("#mw-sidebar-modules-list .modules-list > li");
            } else {
                var obj = mw.$("#mw-sidebar-layouts-list .modules-list > li");
            }
            if (!value) {
                $('.mw-sidebar-search-clear-x-btn', '.' + what).hide();
                obj.show();
                return;
            }

            $('.mw-sidebar-search-clear-x-btn', '.' + what).show();

            var value = value.toLowerCase();

            var numberOfResults = 0;

            var yourArray = [];
            $(obj).each(function () {

                var show = false;

                var description = $(this).attr('description') || false;
                var description = description || $(this).attr('data-filter');
                var title = $(this).attr('title') || false;
                var template = $(this).attr('template') || false;

                if (
                    !!title && title.toLowerCase().contains(value)
                    || (!!description && description.toLowerCase().contains(value))
                    || (!!template && template.toLowerCase().contains(value))

                ) {
                    var show = true;
                }

                if (!show) {
                    $(this).hide();

                } else {
                    $(this).show();
                    numberOfResults++;
                }
            });

            if (numberOfResults == 0) {
                $('.mw-search-no-results', '.' + what).show();
            } else {
                $('.mw-search-no-results', '.' + what).hide();
            }

        }

        function mwCheckScrollBoxPosition() {
            var scrollBox = $(".mw-scroll-box").scrollTop();

            if (scrollBox == 0) {
                $('#search-modules-and-layouts').removeClass('scroll-box-not-top');
            } else {
                $('#search-modules-and-layouts').addClass('scroll-box-not-top');
            }
        }

        $(".mw-scroll-box").on('scroll', function () {
            mwCheckScrollBoxPosition();
        });

        $(document).ready(function () {
            mw.tabs({
                nav: '#mw-modules-layouts-tabsnav  .tabnav',
                tabs: '#mw-modules-layouts-tabsnav .tabitem',
                onclick: function (currentTab, event, index) {
                    $('.tab-title').hide();
                    $('.tab-title-' + index).show();
                }
            });

            mwCheckScrollBoxPosition();

            $('#mw-modules-layouts-tabsnav .tabnav').on('click', function () {
                $('#modules-and-layouts-sidebar .mw-ui-box').scrollTop(0);
            })
        });

        $(window).on('resize load orientationchange', function () {
            var root = document.querySelector('#modules-and-layouts-sidebar');
            if (root !== null) {
              var el = root.querySelectorAll('.mw-ui-box');
              for (var i = 0; i < el.length; i++) {
                var h =  (innerHeight - 50 - ($(el[i]).offset().top - $("#live_edit_side_holder").offset().top));
                el[i].style.height = h + 'px'
              }
            }
        });
    </script>
</div>