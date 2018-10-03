<div id="modules-and-layouts-sidebar" class="modules-and-layouts-holder mw-normalize-css">
    <a href="javascript:;" title="<?php _e("Open/Close menu"); ?>" data-id="mw-toolbar-show-sidebar-btn"
       class="sidebar-toggler">
        <div class="i-holder">
            <i class="mwi-hamb"></i>
        </div>
    </a>
    <a href="javascript:;" class="close-sidebar-button" title="<?php _e("Close"); ?>"
       data-id="mw-toolbar-show-sidebar-btn"><i class="mwi-close-thin"></i></a>

    <h3 class="tab-title tab-title-0"><?php _e("Add Layout"); ?></h3>
    <h3 class="tab-title tab-title-1" style="display: none;"><?php _e("Add Module"); ?></h3>
    <h3 class="tab-title tab-title-2" style="display: none;"><?php _e("Settings"); ?></h3>
    <?php /*
    <h3 class="tab-title tab-title-3" style="display: none;"><?php echo("UI Editor"); ?></h3> */ ?>

    <div id="mw-modules-layouts-tabsnav">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn tabnav active"><i class="mwi-desktop-plus"></i> <?php _e("Layouts"); ?></a>
            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-folder"></i> <?php _e("Modules"); ?></a>
            <a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-cog"></i> <?php _e("Settings"); ?></a>
            <?php /*<a href="javascript:;" class="mw-ui-btn tabnav"><i class="mwi-cog"></i> <?php echo("UI Editor"); ?></a>*/ ?>
        </div>


        <div id="search-modules-and-layouts" class="">

            <div class="tab-title tab-title-0 layouts">
                <div class="search-wrapper">
                    <label for="search-input">
                        <i class="mw-icon-search" aria-hidden="true"></i>
                    </label>

                    <input
                        onkeyup="mwSidebarSearchItems(this.value, 'layouts')"
                        class="form-control input-lg"
                        placeholder="Search for Layouts"
                        autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1"
                        data-id="mw-sidebar-search-input-for-modules-and-layouts">

                    <a
                        href="javascript:mwSidebarSearchClear('layouts');"
                        class="mw-sidebar-search-clear-x-btn mw-icon-close"
                        aria-hidden="true"
                        style="display: none;"></a>
                </div>

                <p class="mw-search-no-results" style="margin: 35px 0 15px 0; display: none; text-align: center;"><?php _e("No results were found"); ?></p>
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

                <p class="mw-search-no-results" style="margin: 35px 0 15px 0; display: none; text-align: center;"><?php _e("No results were found"); ?></p>
            </div>
        </div>

        <div class="mw-ui-box mw-scroll-box" id="mw-sidebar-modules-and-layouts-holder">
            <div class="mw-ui-box-content tabitem">
                <div data-xmodule type="admin/modules/list_layouts" id="mw-sidebar-layouts-list"></div>
            </div>

            <div class="mw-ui-box-content tabitem" style="display: none">
                <div data-xmodule type="admin/modules/list" id="mw-sidebar-modules-list"></div>
            </div>

            <div class="mw-ui-box-content tabitem mw-live-edit-sidebar-iframe-holder" style="display: none;">
                <?php if (file_exists(TEMPLATE_DIR . 'template_settings.php')) { ?>
                    <iframe class="mw-live-edit-sidebar-settings-iframe"
                            data-src="<?php print api_url() ?>module?id=settings/template&live_edit=true&module_settings=true&type=settings/template&autosize=false"></iframe>
                <?php } ?>
            </div>
            <div class="mw-ui-box-content tabitem css-editor-holder">

                
                <div id="mw-css-editor_____TEMP_REMOVE"></div>
            </div>
        </div>
    </div>

    <script>

        mw.require('prop_editor.js');
        mw.require('color.js');

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

                    $("#search-modules-and-layouts")[index == 2?'hide':'show']()

                }
            });

            mwCheckScrollBoxPosition();

            $('#mw-modules-layouts-tabsnav .tabnav').on('click', function () {
                $('#modules-and-layouts-sidebar .mw-ui-box').scrollTop(0);
            });

            /*

            CSSEditorSchema = [
                {
                    interface:'quatro',
                    label:['Margin top', 'Margin right', 'Margin bottom', 'Margin left'],
                    id:'margin'
                },
                {
                    interface:'quatro',
                    label:['Padding top', 'Padding right', 'Padding bottom', 'Padding left'],
                    id:'padding'
                },
                {
                    interface:'size',
                    label:'Font size',
                    id:'fontSize'
                },
                {
                    interface:'color',
                    label:'Font color',
                    id:'color'
                },
                {
                    interface:'color',
                    label:'Background color',
                    id:'backgroundColor'
                },
                {
                    interface:'select',
                    label:'Font weight',
                    id:'fontWeight',
                    options:['inherit', 'normal', 'bold', 'bolder', 'lighter', 100,200,300,400,500,600,700,800,900]
                },
                {
                    interface:'select',
                    label:'Font style',
                    id:'fontStyle',
                    options:['italic', 'normal']
                },
                {
                    interface:'select',
                    label:'Text transform',
                    id:'textTransform',
                    options:['none', 'uppercase', 'lowercase', 'capitalize']
                },
                {
                    interface:'block',
                    content:'Border radius'
                },
                {
                    interface:'quatro',
                    id:'borderRadius',
                    label:['Top Left', 'Top Right', 'Bottom Left', 'Bottom Right']
                },
                {
                    interface:'file',
                    id:'backgroundImage',
                    label:'Background Image',
                    types:'images'
                }
            ];

            mw.elementCSSEditor = new mw.propEditor.schema({
                schema: CSSEditorSchema,
                element:'#mw-css-editor'
            });

            $(mw.elementCSSEditor).on('change', function(event, property, value){
                mw.$(mw.elementCSSEditor.currentElement).css(property, value);
            });

            mw.on("ElementClick", function(event, el){
                mw.elementCSSEditor.currentElement = el;

                var css = getComputedStyle(el);
                var val = {
                    margin:(css.marginTop + ' ' + css.marginRight + ' ' + css.marginBottom + ' ' + css.marginLeft),
                    padding:(css.paddingTop + ' ' + css.paddingRight + ' ' + css.paddingBottom + ' ' + css.paddingLeft),
                    fontSize:css.fontSize,
                    fontWeight:css.fontWeight,
                    fontStyle:css.fontStyle,
                    textTransform:css.textTransform,
                    color:mw.color.rgbToHex(css.color),
                    backgroundColor:mw.color.rgbToHex(css.backgroundColor),
                    backgroundImage:css.backgroundImage,
                    borderRadius:(css.borderTopLeftRadius + ' ' + css.borderTopRightRadius + ' ' + css.borderBottomLeftRadius + ' ' + css.borderBottomRightRadius),
                };
                mw.elementCSSEditor.setValue(val);
            });

            */

        });


        var setScrollBoxes = function(){
            var root = document.querySelector('#modules-and-layouts-sidebar');
            if (root !== null) {
                var el = root.querySelectorAll('.mw-ui-box');
                for (var i = 0; i < el.length; i++) {
                    var h =  (innerHeight - 50 - ($(el[i]).offset().top - $("#live_edit_side_holder").offset().top));
                    el[i].style.height = h + 'px'
                }
            }
        }

        mw.on('liveEditSettingsReady', function(){
            setScrollBoxes()
        })

        $(window).on('resize orientationchange', function () {
            setScrollBoxes()
        });



    </script>
</div>