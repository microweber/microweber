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

<?php if (isset($edit_page_info['title'])): ?>
    <?php $title_for_input = str_replace('"', '&quot;', $edit_page_info['title']); ?>
<?php endif; ?>
<style>
    #admin-user-nav {
        display: none;
    }
</style>
<?php
if (isset($data['content_type']) and $data['content_type'] == 'page') {
    $parent_page_active = 0;
    if ($data['parent'] != 0 and $data['id'] == 0) {
        $data['parent'] = $recommended_parent = 0;
    } elseif (isset($data['parent'])) {
        $parent_page_active = $data['parent'];
    }
}


if (isset($data['id']) and intval($data['id']) == 0 and isset($data['parent']) and intval($data['parent']) != 0) {
    $parent_data = get_content_by_id($data['parent']);
    if (is_array($parent_data) and isset($parent_data['is_active']) and ($parent_data['is_active']) == 0) {
        $data['is_active'] = 0;
    }
}

if ($edit_page_info['is_shop'] == 1) {
    $type = 'Shop';
} elseif ($edit_page_info['subtype'] == 'dynamic') {
    $type = 'Dynamic page';
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
        document.querySelector('#content-title-field-row .card-header').classList[state ? 'add' : 'remove']('post-header-content-changed')
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
            }
            else {
                $(".top-bar").removeClass("fix-in-header after-fix-in-header");
                //$("#create-content-btn").show()
                clearTimeout(fixinheaderTime)

            }
            var isFixed = (stop > (postHeader.get(0).offsetHeight + (header ? header.offsetHeight : 0) + $(postHeader).offset().top));
            postHeader[ isFixed ? 'addClass' : 'removeClass' ]('fixed')
            postHeader.width( isFixed ? postHeader.parent().width() : 'auto' )


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

            if(val != slug){
                $(field).val(slug);
            }

        }

        slugEdited = !(mw.url.windowHashParam('action') || '').includes('new:');
        slugFromTitle = function () {
            if (slugEdited === false) {
                var slug = mw.slug.create($('#content-title-field').val());
                $('.js-slug-base-url-changed').val(slug);
                $('.js-slug-base-url').text(slug);
            }
        }

    </script>



    <form method="post" <?php if ($just_saved != false) : ?> style="display:none;" <?php endif; ?> class="mw_admin_edit_content_form <?php if($wrapper_class=='in-popup'){ ?> mw_admin_edit_content_form_in_popup <?php } ?> " action="<?php echo $formActionUrl; ?>" id="quickform-edit-content" autocomplete="off">

        <?php if ($data['id'] > 0): ?>
            <input name="_method" type="hidden" value="PATCH">
        <?php endif; ?>

        <input type="hidden" name="id" id="mw-content-id-value" value="<?php print $data['id']; ?>"/>
        <input type="hidden" name="subtype" id="mw-content-subtype" value="<?php print $data['subtype']; ?>"/>
        <input type="hidden" name="subtype_value" id="mw-content-subtype-value-<?php print $rand; ?>" value="<?php print $data['subtype_value']; ?>"/>
        <input type="hidden" name="content_type" id="mw-content-type mw-content-type-value-<?php print $rand; ?>" value="<?php print $data['content_type']; ?>"/>
        <input type="hidden" name="parent" id="mw-parent-page-value-<?php print $rand; ?>" value="<?php print $data['parent']; ?>" class=""/>
        <input type="hidden" name="layout_file" id="mw-layout-file-value-<?php print $rand; ?>" value="<?php print $data['layout_file']; ?>"/>
        <input type="hidden" name="active_site_template" id="mw-active-template-value-<?php print $rand; ?>" value="<?php print $data['active_site_template']; ?>"/>

        <script type="text/javascript">
        $(document).ready(function () {
           $('.mw-edit-page-layout-selector').change(function() {
              if ($(this).val() == 'layouts__blog.php') {
                  $('#mw-content-subtype').val('dynamic');
                  $('#mw-content-type').val('page');
              }
              if ($(this).val() == 'layouts__shop.php') {
                    $('#mw-content-subtype').val('dynamic');
                    $('#mw-content-type').val('page');
                    $('#is_shop').attr('checked','checked');
                    $('#is_shop').trigger('change');
              }
           });
        });
        </script>

        <?php
        $backToLink = '';
        $typeIcon = 'mdi-text';
        if ($type == 'Product') {
            $backToLink = route('admin.product.index');
            $typeIcon = 'mdi-shopping';
        } else if ($type == 'Product variant') {
            $backToLink = route('admin.product_variant.index');
            $typeIcon = 'mdi-shopping';
        } elseif ($type == 'Post') {
            $backToLink = route('admin.post.index');
            $typeIcon = 'mdi-text';
        } elseif ($type == 'Page') {
            $backToLink = route('admin.page.index');
            $typeIcon = 'mdi-file-document';
        }
        ?>

        <div class="position-relative">
            <div class="main-toolbar mw-modules-toolbar-back-button-holder">
                <a href="<?php echo $backToLink; ?>" class="btn btn-link text-silver px-0">
                    <i class="mdi mdi-chevron-left"></i> Back to <?php echo $type; ?>s
                </a>
            </div>
        </div>


        <div class="row">
            <div class="col-md-8 manage-content-body">



                <?php if(isset($data['is_deleted']) and $data['is_deleted']) :  ?>
                    <?php include (__DIR__.'/content_delete_btns.php')?>
                <?php endif; ?>



                <div class="content-title-field-row card style-1 mb-3 border-0" id="content-title-field-row">
                    <div class="card-header-fix">
                        <div class="card-header">
                            <h5><i class="mdi <?php echo $typeIcon; ?> text-primary mr-3"></i> <strong><?php _e($action_text); ?></strong></h5>


                            <div id="content-title-field-buttons" class="mw-page-component-disabled">


                               <?php

                                if($wrapper_class=='in-popup'){ ?>
                                    <?php if (isset($data['url']) and $data['id'] > 0) { ?>
                                        <a  title="<?php _ejs("Live Edit"); ?>" href="<?php print content_link($data['id']) ?>?editmode=y" class="btn   btn-outline-primary  btn-sm  btn-sm-only-icon mw-admin-go-live-now-btn mx-1">
                                            </i><span ><?php _e("Live Edit"); ?></span>
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>

                                    <?php if (isset($data['url']) and $data['id'] > 0) { ?>
                                        <a  title="<?php _ejs("Live Edit"); ?>" href="<?php print content_link($data['id']) ?>?editmode=y" class="btn   btn-outline-primary  btn-sm  btn-sm-only-icon mw-admin-go-live-now-btn mx-1">
                                            </i><span><?php _e("Live Edit"); ?></span>
                                        </a>
                                    <?php } ?>

                                <?php } ?>

                                <button id="js-admin-save-content-main-btn" type="submit"   class="btn btn-sm btn-success btn-save js-bottom-save" form="quickform-edit-content"><span><?php _e('Save'); ?></span></button>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($edit_page_info['title'])): ?>
                        <div class="card-body pt-3">

                            <div class="form-group" id="slug-field-holder">
                                <label class="control-label"><?php _e($type) ?> <?php _e("title"); ?></label>

                                <?php
                                $contentModel = \MicroweberPackages\Content\Content::where('id', $data['id'])->first();
                                $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                                ?>

                                <?php
                                echo $formBuilder->text('title')
                                    ->setModel($contentModel)
                                    ->value($title_for_input)
                                    ->id('content-title-field')
                                    ->onkeyup('slugFromTitle();')
                                    ->autocomplete(false);
                                ?>

                                <?php
                                if (!\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled()):
                                ?>
                                <div class="mw-admin-post-slug">
                                    <i class="mdi mdi-link mdi-20px lh-1_3 mr-1 text-silver float-left" title="Copy link"  onclick="copy_url_of_page();" style="cursor: copy"></i>
                                    <span class="mw-admin-post-slug-text">
                                            <?php
                                            if (isset($data['slug_prefix_url'])) {
                                                $site_prefix_url = $data['slug_prefix_url'];
                                            } else {
                                                $site_prefix_url = site_url();
                                            }
                                            ?>

                                        <span class="text-silver" id="slug-base-url" ><?php print $site_prefix_url; ?></span>
                                        <span class="contenteditable js-slug-base-url" data-bs-toggle="tooltip" data-title="edit" data-placement="right" contenteditable="true"><?php print $data['url']; ?></span>
                                    </span>
                                  </div>

                                <div class="d-none">
                                    <input autocomplete="off" name="url" id="edit-content-url" class="js-slug-base-url-changed edit-post-slug" type="text" value="<?php print $data['url']; ?>"/>

                                    <script>


                                        $('.js-slug-base-url').on('paste', function (e) {
                                            e.preventDefault();
                                            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                                            document.execCommand("insertHTML", false, text);
                                            if(this.innerHTML.length > mw.slug.max) {
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
                                            if (mw.event.is.enter(e)  ) {
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


                                         copy_url_of_page =function(){
                                            var site_url =  $('#slug-base-url').html();
                                            var slug_base_url =  $('.js-slug-base-url').html();
                                            var url = site_url + slug_base_url ;
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
                                    <label class="control-label"><?php _e($type) ?> <?php _e("Url"); ?></label>

                                    <?php
                                    echo $formBuilder->text('url')
                                        ->setModel($contentModel)
                                        ->prepend('<div class="input-group-prepend">
                                             <span class="input-group-text"><i class="mdi mdi-link text-silver"></i></span>
                                             </div>')
                                        ->value($data['url'])
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

                                                <label class="control-label" title="Content Body"><?php  _e("Description"); ?></label>
                                                <?php
                                                echo $formBuilder->mwEditor('content_body')
                                                    ->setModel($contentModel)
                                                    ->value($data['content_body'])
                                                    ->autocomplete(false);
                                                ?>
                                        <?php else: ?>

                                                <label class="control-label"><?php  _e("Content"); ?></label>
                                            <?php
                                            echo $formBuilder->mwEditor('content')
                                                ->setModel($contentModel)
                                                ->value($data['content'])
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
                                             if (typeof(mw.adminPagesTree) != 'undefined') {
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
                                        <div class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?> title="<?php print $title; ?>"><?php print $html; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php $custom_title_ui = mw()->module_manager->ui('content.edit.title.end'); ?>
                                <?php if (!empty($custom_title_ui)): ?>
                                    <?php foreach ($custom_title_ui as $item): ?>
                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                        <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                        <div class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?> title="<?php print $title; ?>"><?php print $html; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="admin-manage-content-wrap">

                    <?php if (isset($data['content_type']) and ($data['content_type'] == 'page')): ?>
                        <?php if (isset($data['id']) and ($data['id'] == 0)): ?>
                            <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes" template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>"/>
                        <?php else: ?>
                            <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes" template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" small="true" layout_file"="<?php print $data['layout_file']; ?>"   />
                        <?php endif; ?>

                        <?php
                        $data['recommended_parent'] = $recommended_parent;
                        $data['active_categories'] = $categories_active_ids;
                        ?>
                    <?php else: ?>
                        <div id="mw-admin-edit-content-main-area"></div>
                    <?php endif; ?>

                    <?php if (isset($data['subtype']) and $data['subtype'] == 'dynamic' and (isset($data['content_type']) and $data['content_type'] == 'page')): ?>
                        <script>
                            mw.$("#quick-add-post-options-item-template-btn").hide();
                        </script>
                    <?php endif; ?>

                    <div class="mw-admin-edit-content-holder">
                        <?php include(__DIR__ . '/tabs.php'); ?>
                    </div>

                    <?php event_trigger('mw_admin_edit_page_footer', $data); ?>

                    <?php include(__DIR__ . '/edit_default_scripts.php'); ?>
                </div>
            </div>

            <?php include 'edit_default_sidebar.php'; ?>
        </div>
    </form>
</div>

<script>
    addEventListener('load', function (){
        mw.element('.mw_admin_edit_content_form [name]').on('input', function (){
            contentChanged(true)
        });
    });
</script>
