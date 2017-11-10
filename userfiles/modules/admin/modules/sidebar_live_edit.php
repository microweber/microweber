<div id="modules-and-layouts-sidebar" class="modules-and-layouts-holder mw-normalize-css">
    <a href="javascript:;" title="<?php _e("Open/Close menu"); ?>" data-id="mw-toolbar-show-sidebar-btn" class="sidebar-toggler">
        <div class="i-holder">
            <i class="mwi-hamb"></i>
        </div>
    </a>

    <h3>Settings</h3>
    <div id="mw-modules-layouts-tabsnav">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn tabnav active"><i class="mwi-desktop-plus"></i> Layouts</a>

            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-folder"></i> Modules</a>
            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-cog"></i> Settings</a>
        </div>

        <div id="search-modules-and-layouts" class="">
            <div class="search-wrapper">
                <label for="search-input">
                    <i class="mw-icon-search" aria-hidden="true"></i>
                </label>
                <input class="form-control input-lg" placeholder="Search" autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1">
                <a id="search-clear" href="#" class="mw-icon-close" aria-hidden="true"></a>
            </div>
        </div>

        <div class="mw-ui-box mw-scroll-box">
            <div class="mw-ui-box-content tabitem">
                <module type="admin/modules/list_layouts"/>
            </div>

            <div class="mw-ui-box-content tabitem" style="display: none">
                <module type="admin/modules/list"/>
            </div>

            <div class="mw-ui-box-content tabitem" style="display: none">
                Contact - Lorem Ipsum
            </div>
        </div>
    </div>

    <script>
        function checkScrollBoxPosition() {
            var scrollBox = $(".mw-scroll-box").scrollTop();

            if (scrollBox == 0) {
                $('#search-modules-and-layouts').removeClass('scroll-box-not-top');
            } else {
                $('#search-modules-and-layouts').addClass('scroll-box-not-top');
            }
        }

        $(".mw-scroll-box").on('scroll', function () {
            checkScrollBoxPosition();
        });

        $(document).ready(function () {
            mw.tabs({
                nav: '#mw-modules-layouts-tabsnav  .tabnav',
                tabs: '#mw-modules-layouts-tabsnav .tabitem'
            });

            checkScrollBoxPosition();
        });

        $(window).on('resize load orientationchange', function () {
            var root = document.querySelector('#modules-and-layouts-sidebar');

            if (root !== null) {
                var el = root.querySelectorAll('.mw-ui-box');
                for (var i = 0; i < el.length; i++) {
                    console.log(el[i])
                    el[i].style.height = (innerHeight - 141) + 'px'
                }

            }
        })

    </script>
</div>