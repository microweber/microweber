<script>
    $(document).ready(function () {
        if (window.thismodal && thismodal.resize) {
            thismodal.resize(991);
        }
    });
</script>
<?php

$edit_page_info = $data;

if (!isset($edit_page_info['title'])) {
    $edit_page_info['title'] = _e('Content title', true);
}

$quick_edit = false;

if (isset($params['quick_edit']) and $params['quick_edit']) {
    $quick_edit = true;
}

?>
<?php
//dump($data);
if (isset($data['content_type']) and ($data['content_type'] == 'page') and $data['id'] == 0) {
    if (isset($_GET['layout'])) {
        $data['layout_file'] = (string)$_GET['layout'];
        $data['preview_layout_file'] = (string)$_GET['layout'];


        $layout_details_for_new_page = app()->layouts_manager->get_layout_details([
            'layout_file' => $data['layout_file'],
            'active_site_template' => $data['active_site_template']

        ]);
        if ($layout_details_for_new_page) {
            if (isset($layout_details_for_new_page['content_type']) and $layout_details_for_new_page['content_type'] == 'dynamic') {
                $data['subtype'] = $layout_details_for_new_page['content_type'];
            }
            if (isset($layout_details_for_new_page['is_shop']) and ($layout_details_for_new_page['is_shop'] == 'y' or $layout_details_for_new_page['is_shop'] == '1')) {
                $data['is_shop'] = 1;
            }
        }
    }
}
?>
<?php if (isset($edit_page_info['title'])): ?>
    <?php $title_for_input = str_replace('"', '&quot;', $edit_page_info['title']); ?>
<?php endif; ?>
<style>
    #admin-user-nav {
        display: none;
    }
</style>
<?php

//if (isset($data['content_type']) and $data['content_type'] == 'page' and !isset($data['parent'])) {
//    $parent_page_active = 0;
//    if ($data['parent'] != 0 and $data['id'] == 0) {
//        $data['parent'] = $recommended_parent = 0;
//    } elseif (isset($data['parent'])) {
//        $parent_page_active = $data['parent'];
//    }
//}


if (isset($data['id']) and intval($data['id']) == 0 and isset($data['parent']) and intval($data['parent']) != 0) {
    $parent_data = get_content_by_id($data['parent']);
    if (is_array($parent_data) and isset($parent_data['is_active']) and ($parent_data['is_active']) == 0) {
        $data['is_active'] = 0;
    }
}

if ($edit_page_info['is_shop'] == 1) {
    $type = 'Shop';
} elseif ($edit_page_info['subtype'] == 'dynamic') {
    $type = 'Blog';
} elseif ($edit_page_info['subtype'] == 'post') {
    $type = 'Post';
} elseif ($edit_page_info['content_type'] == 'product') {
    $type = 'Product';
} elseif ($edit_page_info['content_type'] == 'product_variant') {
    $type = 'Product variant';
} elseif ($edit_page_info['content_type'] == 'post') {
    $type = 'Post';
} elseif ($edit_page_info['content_type'] == 'page') {
    $type = 'Page';
} else {
    $type = 'Page';
}

$action_text = _e($type, true);
if (isset($edit_page_info['id']) and intval($edit_page_info['id']) != 0) {
    $action_text = _e("Editing " . strtolower($type), true);
} else {
    $action_text = _e("Add " . strtolower($type), true);
}

if (isset($edit_page_info['content_type']) and $edit_page_info['content_type'] == 'post' and isset($edit_page_info['subtype'])) {
    //     $action_text2 = $edit_page_info['subtype'];
}
?>

<?php if (!$quick_edit) { ?>
    <script>
        $(document).ready(function () {
            $('.fade-window .btn-fullscreen').on('click', function () {
                $(this).toggleClass('hidden');
                $('.fade-window .btn-close').toggleClass('hidden');
                $('.fade-window').toggleClass('closed');

            });
            $('.fade-window .btn-close').on('click', function () {
                $(this).toggleClass('hidden');
                $('.fade-window .btn-fullscreen').toggleClass('hidden');
                $('.fade-window').toggleClass('closed');
            });


            $('.fade-window').on('scroll', function () {
                var otop = $('.mw-iframe-editor').offset().top;
                $('#mw-admin-content-iframe-editor iframe').contents().find('#mw-admin-text-editor')[otop <= 0 ? 'addClass' : 'removeClass']('scrolled').css({
                    top: otop <= 0 ? Math.abs(otop) : 0
                });
            })

        });
    </script>
<?php } ?>

<script>
    $(document).ready(function () {
        $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
    });
</script>

<script>

    var contentChanged = function (state) {
        //   document.querySelector('.btn-save').disabled = !state;
        mw.askusertostay = state;

        //    !!!!!!!!!! Must revert !!!!!!!!!!!!


        //  mw.askusertostay = false;
        // document.querySelector('#content-title-field-row .card-header').classList[state ? 'add' : 'remove']('post-header-content-changed')

        $('#js-admin-save-content-main-btn').addClass('btn-ghost-success active').removeClass('btn-dark')
    }

    $(document).ready(function () {
        var all = $(window);
        var header = document.querySelector('#mw-admin-container header');
        var postHeader = mw.element(document.querySelector('#content-title-field-row .card-header'));
        all.push(document)
        all.on('scroll load resize', function () {
            var stop = $(this).scrollTop(),
                otop = $('.mw-iframe-editor').offset().top,
                tbheight = $('.admin-toolbar').outerHeight(),
                is = (stop + tbheight) >= otop;


            $('#mw-admin-content-iframe-editor iframe').contents().find('#mw-admin-text-editor')[is ? 'addClass' : 'removeClass']('scrolled').css({
                top: is ? Math.abs((stop + tbheight) - otop) : 0
            });
            var fixinheaderTime = null;
            if (stop > $(".admin-toolbar").height()) {

                $(".top-bar").addClass("fix-in-header").css('left', $('.window-holder').offset().left);
                fixinheaderTime = setTimeout(function () {
                    $(".top-bar").addClass("after-fix-in-header")
                    // $("#create-content-btn").hide()
                }, 10)
            } else {
                $(".top-bar").removeClass("fix-in-header after-fix-in-header");
                //$("#create-content-btn").show()
                clearTimeout(fixinheaderTime)

            }
            var isFixed = (stop > (postHeader.get(0).offsetHeight + (header ? header.offsetHeight : 0) + $(postHeader).offset().top));
            postHeader[isFixed ? 'addClass' : 'removeClass']('fixed')
            postHeader.width(isFixed ? postHeader.parent().width() : 'auto')


        });

    });
</script>

<?php
$wrapper_class = 'in-window';
if (isset($params['live_edit'])) {
    $wrapper_class = 'in-popup';
}
if (isset($params['quick_edit'])) {
    //   $wrapper_class = 'in-popup';
}


?>

<div class="<?php echo $wrapper_class; ?>">

    <?php

    $data['id'] = intval($data['id']);
    $formActionUrl = site_url() . 'api/save_content_admin';
    if (isset($data['content_type']) and $data['content_type'] == 'page') {
        $formActionUrl = route('api.page.index');
        if ($data['id'] > 0) {
            $formActionUrl = route('api.page.update', $data['id']);
        }
    }
    if (isset($data['content_type']) and $data['content_type'] == 'product') {
        $formActionUrl = route('api.product.index');
        if ($data['id'] > 0) {
            $formActionUrl = route('api.product.update', $data['id']);
        }
    }
    if (isset($data['content_type']) and $data['content_type'] == 'product_variant') {
        $formActionUrl = route('api.product_variant.index');
        if ($data['id'] > 0) {
            $formActionUrl = route('api.product_variant.update', $data['id']);
        }
    }
    if (isset($data['content_type']) and $data['content_type'] == 'post') {
        $formActionUrl = route('api.post.index');
        if ($data['id'] > 0) {
            $formActionUrl = route('api.post.update', $data['id']);
        }
    }


    ?>
    <script>
        slugFromUrlField = function (field) {
            var val = $(field).val();


            var slug = mw.slug.create(val);

            if (val != slug) {
                $(field).val(slug);
            }

        }

        slugEdited = !(mw.url.windowHashParam('action') || '').includes('new:');
        slugFromTitle = function (el) {

            var val = $('#content-title-field').val();


            if (slugEdited === false) {
                var slug = mw.slug.create(val);
                $('.js-slug-base-url-changed').val(slug);
                $('.js-slug-base-url').text(slug);
            }
        }
        titlePreviewChange = function (el) {
            if (typeof el === 'undefined') {
                return
            }
            var val = $(el).val();
            $('#js-edit-content-dynamic-title-display').text(val);
        }

    </script>


    <?php
    $quickAdd = false;
    if (isset($_GET['quickAdd']) and $_GET['quickAdd']) {
        $quickAdd = boolval($_GET['quickAdd']);
    }


    ?>

    <?php if ($quickAdd) { ?>


        <style>
            .js-collapse-inner-page-menu {
                display: none !important;
            }

            #mw-edit-page-editor-holder {
                display: none !important;
            }

            #post-media-card-header {
                display: none !important;
            }

            #settings-tabs {
                display: none !important;
            }

            #edit-post-gallery-main {
                display: none !important;
            }

            #js-admin-add-more-content-main-btn {
                display: none !important;
            }

            #js-admin-go-live-edit-main-btn {
                display: none !important;
            }

            .manage-content-sidebar {
                display: none !important;
            }

            .mw-toolbar-back-button-wrapper {
                display: none !important;
            }

            .mw-admin-post-slug {
                display: none !important;
            }
        </style>

    <?php } ?>


    <form method="post" <?php if ($just_saved != false) : ?> style="display:none;" <?php endif; ?>
          class="mw_admin_edit_content_form <?php if ($wrapper_class == 'in-popup') { ?> mw_admin_edit_content_form_in_popup <?php } ?> "
          action="<?php echo $formActionUrl; ?>" id="quickform-edit-content" autocomplete="off">

        <?php if ($data['id'] > 0): ?>
            <input name="_method" type="hidden" value="PATCH">
        <?php endif; ?>

        <input type="hidden" name="id" id="mw-content-id-value" value="<?php print $data['id']; ?>"/>
        <input type="hidden" name="subtype" id="mw-content-subtype" value="<?php print $data['subtype']; ?>"/>
        <input type="hidden" name="subtype_value" id="mw-content-subtype-value-<?php print $rand; ?>"
               value="<?php print $data['subtype_value']; ?>"/>
        <input type="hidden" name="content_type" id="mw-content-type mw-content-type-value-<?php print $rand; ?>"
               value="<?php print $data['content_type']; ?>"/>
        <input type="hidden" name="parent" id="mw-parent-page-value-<?php print $rand; ?>"
               value="<?php print $data['parent']; ?>" class=""/>
        <input type="hidden" name="layout_file" id="mw-layout-file-value-<?php print $rand; ?>"
               value="<?php print $data['layout_file']; ?>"/>
        <input type="hidden" name="active_site_template" id="mw-active-template-value-<?php print $rand; ?>"
               value="<?php print $data['active_site_template']; ?>"/>


        <script type="text/javascript">
            $(document).ready(function () {
                $('.mw-edit-page-layout-selector').change(function () {
                    if ($(this).val() == 'layouts__blog.php') {
                        $('#mw-content-subtype').val('dynamic');
                        $('#mw-content-type').val('page');
                    }
                    if ($(this).val() == 'layouts__shop.php') {
                        $('#mw-content-subtype').val('dynamic');
                        $('#mw-content-type').val('page');
                        $('#is_shop').attr('checked', 'checked');
                        $('#is_shop').trigger('change');
                    }
                });
            });
        </script>

        <?php
        $backToLink = '';
        $typeIcon = 'mdi-text';
        $backToTypeText = 'Content';
        if ($type == 'Product') {
            $backToLink = route('admin.product.index');
            $typeIcon = 'mdi-shopping';
            $backToTypeText = 'Products';
        } else if ($type == 'Product variant') {
            $backToLink = route('admin.product_variant.index');
            $typeIcon = 'mdi-shopping';
            $backToTypeText = 'Product variants';
        } elseif ($type == 'Post') {
            $backToLink = route('admin.post.index');
            $typeIcon = 'mdi-text';
        } elseif ($type == 'Blog') {
            $backToLink = route('admin.post.index');
            $typeIcon = 'mdi-text';
            $backToTypeText = 'Blog';

        } elseif ($type == 'Page') {
            $backToLink = route('admin.page.index');
            $typeIcon = 'mdi-file-document';
            $backToTypeText = 'Pages';
        }
        ?>

        <script>
            addEventListener('load', function () {
                $('.js-collapse-inner-page-menu').collapseNav({
                    responsive: 1,
                    more_text: 'More Button',
                    mobile_break: 800,
                });
            })
        </script>

        <div class="row" x-data="{showEditTab: 'details'}">
            <div class="col-lg-8 manage-content-body px-5">

                <?php if (isset($data['is_deleted']) and $data['is_deleted']) : ?>
                    <?php include(__DIR__ . '/content_delete_btns.php') ?>
                <?php endif; ?>

                <div class="d-flex js-collapse-inner-page-menu justify-content-between align-items-center mb-4">
                    <div class="d-flex flex-wrap gap-md-4 gap-3">
                        <a href="#" x-on:click="showEditTab = 'details'" :class="{ 'active': showEditTab == 'details' }"
                           class="btn btn-link text-decoration-none mw-admin-action-links">
                            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20"
                                 viewBox="0 -960 960 960" width="20">
                                <path
                                    d="M144-168v-412q-23-7-35.5-26T96-648v-144q0-29.7 21.15-50.85Q138.3-864 168-864h624q29.7 0 50.85 21.15Q864-821.7 864-792v144q0 23-12.5 42T816-580v411.926Q816-138 794.85-117 773.7-96 744-96H216q-29.7 0-50.85-21.15Q144-138.3 144-168Zm72-408v408h528v-408H216Zm576-72v-144H168v144h624ZM384-408h192v-72H384v72ZM216-168v-408 408Z"/>
                            </svg>
                            <?php echo $type; ?> <?php echo _e('Details'); ?>
                        </a>

                        <a href="#" x-on:click="showEditTab = 'customFields'"
                           :class="{ 'active': showEditTab == 'customFields' }"
                           class="btn btn-link text-decoration-none mw-admin-action-links">
                            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20"
                                 viewBox="0 -960 960 960" width="20">
                                <path
                                    d="M432-432h264v-264H432v264ZM216-144q-29.7 0-50.85-21.15Q144-186.3 144-216v-528q0-29.7 21.15-50.85Q186.3-816 216-816h528q29.7 0 50.85 21.15Q816-773.7 816-744v528q0 29.7-21.15 50.85Q773.7-144 744-144H216Zm0-72h528v-528H216v528Zm0-528v528-528Z"/>
                            </svg>
                            <?php echo _e('Custom Fields'); ?>
                        </a>

                        <a href="#" x-on:click="showEditTab = 'seo'" :class="{ 'active': showEditTab == 'seo' }"
                           class="btn btn-link text-decoration-none mw-admin-action-links">
                            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20"
                                 viewBox="0 -960 960 960" width="20">
                                <path
                                    d="m107-246-59-42 192-312 120 144 168-264 120 168q-21 0-40.5 3T569-539l-38-52-163 257-119-143-142 231ZM861-48 738-171q-20 13-42.5 20t-47.5 7q-70 0-119-49t-49-119q0-70 49-119t119-49q70 0 119 49t49 119q0 25-7 47.5T789-222L912-99l-51 51ZM648-216q40 0 68-28t28-68q0-40-28-68t-68-28q-40 0-68 28t-28 68q0 40 28 68t68 28Zm78-323q-18-7-38-10t-40-3l205-312 59 42-186 283Z"/>
                            </svg>
                            <?php echo _e('SEO'); ?>

                        </a>

                        <a href="#" x-on:click="showEditTab = 'advanced'"
                           :class="{ 'active': showEditTab == 'advanced' }"
                           class="btn btn-link text-decoration-none link mw-admin-action-links">
                            <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20"
                                 viewBox="0 -960 960 960" width="20">
                                <path
                                    d="m367-80-15-126q-10-6-22-13t-22-13l-117 49L78-378l100-76v-52L78-582l113-195 117 49q10-6 22-13t22-13l15-126h226l15 126q10 6 22 13t22 13l117-49 113 195-99 76v52l99 76-113 195-117-49q-10 6-22 13t-22 13L593-80H367Zm113-257q59 0 101-42t42-101q0-59-42-101t-101-42q-59 0-101 42t-42 101q0 59 42 101t101 42Zm0-84q-24 0-41.5-17.5T421-480q0-24 17.5-41.5T480-539q24 0 41.5 17.5T539-480q0 24-17.5 41.5T480-421Zm1-59Zm-41 316h80l12-104q29-8 56-24t49-38l97 41 40-66-85-65q5-14 7-29t2-31q0-14-2-28.5t-6-30.5l85-66-40-66-97 42q-23-23-49.5-38.5T533-691l-13-105h-80l-13 105q-29 8-55.5 23.5T323-630l-97-41-40 66 84 65q-4 16-6 31t-2 29q0 14 2 28.5t6 30.5l-84 66 40 66 97-41q22 22 48.5 37.5T427-269l13 105Z"/>
                            </svg>
                            <?php echo _e('Advanced'); ?>
                        </a>

                    </div>

                    <!--                    <div id="content-title-field-buttons" class="mw-page-component-disabled">
                        <button id="js-admin-save-content-main-btn" type="submit" class="btn btn-outline-dark mw-admin-bold-outline-dark btn-save js-bottom-save ms-atuo" form="quickform-edit-content"><span>
                                <svg fill="currentColor" class="mw-admin-save-btn-svg me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M840-683v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h503l157 157Zm-60 27L656-780H180v600h600v-476ZM479.765-245Q523-245 553.5-275.265q30.5-30.264 30.5-73.5Q584-392 553.735-422.5q-30.264-30.5-73.5-30.5Q437-453 406.5-422.735q-30.5 30.264-30.5 73.5Q376-306 406.265-275.5q30.264 30.5 73.5 30.5ZM233-584h358v-143H233v143Zm-53-72v476-600 124Z"/></svg>

                                <span class="mw-admin-save-btn-loading spinner-border spinner-border-sm me-2" style="display: none;" role="status"></span>

                                <?php _e('Save'); ?></span>
                        </button>
                    </div>-->

                    <!--                    <a href="-->
                    <?php //echo route('admin.product.create') ?><!--" class="btn btn-link font-weight-bold tblr-body-color mw-admin-action-links text-decoration-none ms-3">-->
                    <!--                        <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>-->
                    <!--                       --><?php //_e("New Product") ?>
                    <!--                    </a>-->

                </div>

                <?php
                /**
                 * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
                 */

                $contentModel = \MicroweberPackages\Content\Models\Content::where('id', $data['id'])->first();
                $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
                ?>
                <div x-data="{ title: '<?php echo $title_for_input ?>' }">
                    <div x-show="showEditTab=='details'">
                        <div class="card manage-content-card mb-5 ">
                            <div class="card-body" id="content-title-field-row">

                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <h1 class="main-pages-title"><strong id="js-edit-content-dynamic-title-display"
                                                                             x-text="title"><?php _e($action_text); ?></strong>
                                        </h1>
                                    </div>


                                    <?php if (isset($edit_page_info['title'])): ?>

                                        <div class=" ">
                                            <div class="form-group" id="slug-field-holder">
                                                <label
                                                    class="form-label"><span><?php _e($type) ?></span> <?php _e("title"); ?></label>


                                                <?php
                                                /**
                                                 * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
                                                 */


                                                echo $formBuilder->text('title')
                                                    ->setModel($contentModel)
                                                    //  ->xModel('title')
                                                    ->value($title_for_input)
                                                    ->id('content-title-field')
                                                    ->onkeyup('slugFromTitle(this);titlePreviewChange(this);')
                                                    ->autocomplete(false);
                                                ?>

                                                <?php
                                                if (!\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled()):
                                                    ?>
                                                    <div class="mw-admin-post-slug">
                                                        <i class="mdi mdi-link mdi-20px lh-1_3 mr-1 text-silver float-left"
                                                           title="Copy link" onclick="copy_url_of_page();"
                                                           style="cursor: copy"></i>
                                                        <span class="mw-admin-post-slug-text">
                                            <?php
                                            if (isset($data['slug_prefix_url'])) {
                                                $site_prefix_url = $data['slug_prefix_url'];
                                            } else {
                                                $site_prefix_url = site_url();
                                            }
                                            ?>

                                        <span class="text-silver"
                                              id="slug-base-url"><?php print $site_prefix_url; ?></span>
                                        <span class="contenteditable js-slug-base-url" data-bs-toggle="tooltip"
                                              data-title="edit" data-placement="right"
                                              contenteditable="true"><?php print $data['url']; ?></span>
                                    </span>
                                                    </div>

                                                    <div class="d-none">
                                                        <input autocomplete="off" name="url" id="edit-content-url"
                                                               class="js-slug-base-url-changed edit-post-slug"
                                                               type="text" value="<?php print $data['url']; ?>"/>

                                                        <script>


                                                            $('.js-slug-base-url').on('paste', function (e) {
                                                                e.preventDefault();
                                                                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                                                                document.execCommand("insertHTML", false, text);
                                                                if (this.innerHTML.length > mw.slug.max) {
                                                                    this.innerHTML = this.innerHTML.substring(0, mw.slug.max)
                                                                }
                                                                contentChanged(true)
                                                                slugEdited = true;
                                                            })
                                                                .on('keydown', function (e) {
                                                                    var sel = getSelection();
                                                                    var fn = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                                                                    var collapsedIn = fn === this && sel.isCollapsed;
                                                                    slugEdited = true;
                                                                    contentChanged(true)
                                                                    if (mw.event.is.enter(e)) {
                                                                        e.preventDefault();
                                                                    }
                                                                    if (!mw.event.is.delete(e) && !mw.event.is.backSpace(e) && !e.ctrlKey) {
                                                                        if ($('.js-slug-base-url').html().length >= mw.slug.max && collapsedIn) {
                                                                            e.preventDefault();
                                                                        }
                                                                    }
                                                                })
                                                            $('body').on('blur', '.js-slug-base-url', function () {
                                                                var slug = mw.slug.create($(this).text());
                                                                $('.js-slug-base-url-changed').val(slug);
                                                                $('.js-slug-base-url').text(slug);
                                                            });


                                                            copy_url_of_page = function () {
                                                                var site_url = $('#slug-base-url').html();
                                                                var slug_base_url = $('.js-slug-base-url').html();
                                                                var url = site_url + slug_base_url;
                                                                mw.tools.copy(url);
                                                            }

                                                        </script>
                                                    </div>

                                                <?php
                                                endif;
                                                ?>
                                            </div>


                                            <?php
                                            if (\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled()):
                                                ?>
                                                <div class="form-group">
                                                    <label
                                                        class="form-label"><?php _e($type) ?><?php _e("Slug"); ?></label>

                                                    <?php
                                                    echo $formBuilder->text('url')
                                                        ->setModel($contentModel)
                                                        ->prepend('<div class="input-group-prepend">
                                             <span class="input-group-text"><i class="mdi mdi-link text-silver"></i></span>
                                             </div>')
                                                        ->value($data['slug'])
                                                        ->id('content-slug-field')
                                                        ->oninput('slugFromUrlField(this);')
                                                        ->autocomplete(false);
                                                    ?>
                                                </div>
                                            <?php
                                            endif;
                                            ?>



                                            <?php $content_edit_modules = mw('ui')->module('admin.content.edit.text'); ?>
                                            <?php $modules = array(); ?>
                                            <?php
                                            if (!empty($content_edit_modules) and !empty($data)) {
                                                foreach ($content_edit_modules as $k1 => $content_edit_module) {
                                                    foreach ($data as $k => $v) {
                                                        if (isset($content_edit_module[$k])) {
                                                            $v1 = $content_edit_module[$k];
                                                            $v2 = $v;
                                                            if (trim($v1) == trim($v2)) {
                                                                $modules[] = $content_edit_module['module'];
                                                            }
                                                        }

                                                    }
                                                }
                                                $modules = array_unique($modules);
                                            }
                                            ?>

                                            <div id="mw-edit-page-editor-holder">
                                                <?php event_trigger('content.edit.richtext', $data); ?>
                                                <?php $content_edit_modules = mw()->ui->module('content.edit.richtext'); ?>
                                                <?php $modules = array(); ?>
                                                <?php

                                                if (!empty($content_edit_modules) and !empty($data)) {
                                                    foreach ($content_edit_modules as $k1 => $content_edit_module) {
                                                        if (isset($content_edit_module['module'])) {
                                                            $modules[] = $content_edit_module['module'];
                                                        }
                                                    }
                                                    $modules = array_unique($modules);
                                                }
                                                ?>
                                                <?php if (!empty($modules)): ?>
                                                    <?php foreach ($modules as $module) : ?>
                                                        <?php print load_module($module, $data); ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php if (isset($data['content_type']) and ($data['content_type'] != 'page')): ?>
                                                        <div class="form-group">
                                                            <?php if (isset($data['content_type']) and ($data['content_type'] == 'product')): ?>

                                                                <label class="form-label"
                                                                       title="Content Body"><?php _e("Description"); ?></label>
                                                                <?php
                                                                echo $formBuilder->mwEditor('content_body')
                                                                    ->setModel($contentModel)
                                                                    ->value($data['content_body'])
                                                                    ->onSaveCallback('mw.edit_content.handle_form_submit();')
                                                                    ->autocomplete(false);
                                                                ?>
                                                            <?php else: ?>

                                                                <label
                                                                    class="form-label"><?php _e("Content"); ?></label>
                                                                <?php
                                                                echo $formBuilder->mwEditor('content')
                                                                    ->setModel($contentModel)
                                                                    ->value($data['content'])
                                                                    ->onSaveCallback('mw.edit_content.handle_form_submit();')
                                                                    ->autocomplete(false);
                                                                ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>


                                            </div>

                                            <div>
                                                <script>
                                                    $(document).ready(function () {
                                                        setTimeout(function () {
                                                            if (typeof (mw.adminPagesTree) != 'undefined') {
                                                                mw.adminPagesTree.select({
                                                                    id:<?php print $edit_page_info['id']  ?>,
                                                                    type: 'page'
                                                                })
                                                            }
                                                            contentChanged(false)
                                                        }, 100);
                                                    });
                                                </script>

                                                <?php event_trigger('content.edit.title.after'); ?>
                                                <?php $custom_title_ui = mw()->module_manager->ui('content.edit.title.after'); ?>

                                                <?php if (!empty($custom_title_ui)): ?>
                                                    <?php foreach ($custom_title_ui as $item): ?>
                                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                                        <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                                        <div
                                                            class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                                            title="<?php print $title; ?>"><?php print $html; ?></div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <?php $custom_title_ui = mw()->module_manager->ui('content.edit.title.end'); ?>
                                                <?php if (!empty($custom_title_ui)): ?>
                                                    <?php foreach ($custom_title_ui as $item): ?>
                                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                                        <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                                        <div
                                                            class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                                            title="<?php print $title; ?>"><?php print $html; ?></div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>


                                            <div class="mb-3 images position-relative">
                                                <div class="no-border" id="post-media-card-header">
                                                    <label class="form-label">
                                                        <?php _e('Add media'); ?>
                                                    </label>
                                                    <div class="dropzone mw-dropzone" id="post-file-picker"
                                                         onclick="addImagesToPost()">
                                                        <div class="dz-message">
                                                            <h3 class="dropzone-msg-title"><?php _e("Add file"); ?></h3>
                                                            <span
                                                                class="dropzone-msg-desc"><?php _e("or drop files to upload"); ?></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div>
                                                    <module
                                                        id="edit-post-gallery-main"
                                                        type="pictures/admin"
                                                        class="pictures-admin-content-type-<?php print trim($data['content_type']) ?>"
                                                        for="content"
                                                        content_type="<?php print trim($data['content_type']) ?>"
                                                        for-id="<?php print $data['id']; ?>"/>
                                                </div>
                                            </div>


                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>




                        <?php
                        include_once __DIR__.'/tabs.php';
                        ?>

                    </div>

                    <div x-show="showEditTab=='customFields'">

                        <?php
                        include_once __DIR__.'/custom_fields.php';
                        ?>

                    </div>

                    <div x-show="showEditTab=='seo'">
                        <?php
                        include_once __DIR__.'/seo.php';
                        ?>
                    </div>

                    <div x-show="showEditTab=='advanced'">
                        <?php
                        include_once __DIR__.'/advanced_settings.php';
                        ?>
                    </div>
                </div>


                <?php if (isset($data['subtype']) and $data['subtype'] == 'dynamic' and (isset($data['content_type']) and $data['content_type'] == 'page')): ?>
                    <script>
                        mw.$("#quick-add-post-options-item-template-btn").hide();
                    </script>
                <?php endif; ?>

                <?php
                $data['recommended_parent'] = $recommended_parent;
                $data['active_categories'] = $categories_active_ids;
                ?>


                <?php event_trigger('mw_admin_edit_page_footer', $data); ?>
                <?php include(__DIR__ . '/edit_default_scripts.php'); ?>


            </div>
            <?php include __DIR__.'/edit_default_sidebar.php'; ?>

        </div>
    </form>
</div>

<script>
    addEventListener('load', function () {
        mw.element('.mw_admin_edit_content_form [name]').on('input', function () {
            contentChanged(true)
        });
    });
</script>

