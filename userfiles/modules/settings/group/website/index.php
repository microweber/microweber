<?php must_have_access(); ?>


<script type="text/javascript">
    mw.lib.require('collapse_nav');
</script>


<script>
    $(document).ready(function () {
//         $('.js-anchorific').anchorific({
//             navigation: '.anchorific', // position of navigation
//             headers: 'h5', // headers that you wish to target
//             speed: 200, // speed of sliding back to top
//             anchorText: '#', // prepended or appended to anchor headings
//             top: '.top', // back to top button or link class
//             spyOffset: 0, // specify heading offset for spy scrolling
//         });
//
//         $('.js-anchorific ul').collapseNav({
//             'mobile_break': 320,
// //            'li_class': '',
// //            'li_a_class': '',
// //            'li_ul_class': ''
//         });
    })
</script>
<h1 class="main-pages-title"><?php _e('General'); ?></h1>


<div class="<?php print $config['module_class'] ?> js-anchorific">
    <div class="card mb-7">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e('Seo Settings'); ?></h5>
                    <small class="text-muted"><?php _e('Fill in the fields for maximum results when finding your website in search engines.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">

                                    <?php
                                    $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
                                    ?>

                                    <div class="form-group mb-4">
                                        <label class="form-label"><?php _e("Website Name"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("This is very important for search engines."); ?> <?php _e("Your website will be categorized by many criteria and its name is one of them."); ?></small>
                                        <?php
                                            echo $formBuilder->textOption('website_title', 'website')->attribute('autocomplete', 'off');
                                        ?>
                                    </div>


                                    <div class="form-group mb-4">
                                        <label class="form-label"><?php _e("Website Description"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Describe what your website is about"); ?>.</small>
                                        <?php
                                        echo $formBuilder->textareaOption('website_description', 'website')->rows(7)->attribute('autocomplete', 'off');
                                        ?>
                                    </div>

                                    <?php
                                    /*        <div class="form-group">
                                                <label class="form-label">
                                                    <?php _e("Shop Enable/Disable"); ?>
                                                </label>

                                                <div class="mw-ui-check-selector">
                                                    <label class="mw-ui-check" style="margin-right: 15px;">
                                                        <input name="shop_disabled" class="mw_option_field" onchange="" data-option-group="website" value="n" type="radio" <?php if (get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
                                                        <span></span><span><?php _e("Enable"); ?></span>
                                                    </label>
                                                    <label class="mw-ui-check">
                                                        <input name="shop_disabled" class="mw_option_field" onchange="" data-option-group="website" value="y" type="radio" <?php if (get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
                                                        <span></span> <span><?php _e("Disable"); ?></span>
                                                    </label>
                                                </div>
                                            </div>*/
                                    ?>

                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Website Keywords"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Ex.");?>: <?php _e("Cat, Videos of Cats, Funny Cats, Cat Pictures, Cat for Sale, Cat Products and Food"); ?></small>
                                        <?php
                                        echo $formBuilder->textOption('website_keywords', 'website')->attribute('autocomplete', 'off');
                                        ?>
                                    </div>
                                    <br>
                                    <div class="form-group js-permalink-edit-option-hook">
                                        <label class="form-label"><?php _e("Permalink Settings"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Choose the URL posts & page format."); ?></small>
                                        <?php $permalinkStructures = mw()->permalink_manager->getStructures(); ?>
                                        <?php $currentPremalinkStructure = get_option('permalink_structure', 'website'); ?>


                                        <div class="d-block d-xl-flex align-items-center">
                                            <small class="mr-2 my-2 font-weight-bold"><?php echo mw()->url_manager->site_url(); ?> </small>
                                            <select name="permalink_structure" class="form-select mw_option_field" data-width="100%" data-style="btn-sm" option-group="website">
                                                <?php if (is_array($permalinkStructures)): ?>
                                                    <?php foreach ($permalinkStructures as $structureKey => $structureVal): ?>
                                                        <option value="<?php print $structureKey ?>" <?php if ($currentPremalinkStructure == $structureKey): ?> selected="selected" <?php endif; ?>><?php print $structureVal ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-7">
        <div class="card-body">


            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e('General Settings'); ?></h5>
                    <small class="text-muted"><?php _e('Set regional settings for your website or online store');?> <?php _e('They will also affect the language you use and the fees for the orders.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">


                                    <div class="form-group mb-4">
                                        <label class="form-label"><?php _e("Date Format"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Choose a date format for your website"); ?></small>
                                        <?php $date_formats = app()->format->get_supported_date_formats(); ?>
                                        <?php $curent_val = get_option('date_format', 'website'); ?>
                                        <select name="date_format" class="form-select mw_option_field" data-width="100%" data-size="7" option-group="website">
                                            <?php if (is_array($date_formats)): ?>
                                                <?php foreach ($date_formats as $item): ?>
                                                    <option value="<?php print $item ?>" <?php if ($curent_val == $item): ?> selected="selected" <?php endif; ?>><?php print date($item, time()) ?> - (<?php print $item ?>)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Time Zone"); ?></label>
                                        <small class="text-muted d-block mb-2">Set a time zone</small>
                                        <?php $curent_time_zone = get_option('time_zone', 'website'); ?>
                                        <?php
                                        if ($curent_time_zone == false) {
                                            $curent_time_zone = date_default_timezone_get();
                                        }

                                        $timezones = timezone_identifiers_list(); ?>
                                        <select name="time_zone" class="form-select mw_option_field" data-width="100%" data-size="7" data-live-search="true" option-group="website">
                                            <?php foreach ($timezones as $timezone) {
                                                echo '<option';
                                                if ($timezone == $curent_time_zone) echo ' selected="selected"';
                                                echo '>' . $timezone . '</option>' . "\n";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="padding-top: 0;">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Posts per Page"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Select how many posts or products you want to be shown per page"); ?>?</small>
                                        <select name="items_per_page" class="form-control mw_option_field" type="range" option-group="website">
                                            <?php
                                            $per_page = get_option('items_per_page', 'website');
                                            $found = false;
                                            for ($i = 5; $i < 40; $i += 5) {
                                                if ($i == $per_page) {
                                                    $found = true;
                                                    print '<option selected="selected" value="' . $i . '">' . $i . '</option>';
                                                } else {
                                                    print '<option value="' . $i . '">' . $i . '</option>';
                                                }
                                            }
                                            if ($found == false) {
                                                print '<option selected="selected" value="' . $per_page . '">' . $per_page . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $logo = get_option('logo', 'website');
                                        $nologo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.svg';
                                        if (!$logo) {
                                            $logo = $nologo;
                                        }
                                        ?>
                                        <script>
                                            $(document).ready(function () {
                                                websiteLogo = mw.uploader({
                                                    element: document.getElementById('js-upload-logo-image'),
                                                    filetypes: 'images',
                                                    multiple: false
                                                });
                                                $(websiteLogo).on('FileUploaded', function (a, b) {
                                                  mw.$("#logo-preview").val(b.src).trigger('change');
                                                  mw.$(".js-logo").attr('src', b.src);
                                                   // mw.$("link[rel*='icon']").attr('href', b.src);
                                                });
                                                mw.element('#remove-logo-btn').on('click', function(){
                                                    mw.element('#logo-preview').val('').trigger('change')
                                                    mw.element('.js-logo').attr('src', '<?php print $nologo; ?>');
                                                })
                                            })

                                        </script>
                                        <br>

                                        <label class="form-label"><?php _e("Website Logo"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Select an logo for your website.'); ?></small>
                                        <div class="d-flex">
                                            <div class="avatar img-absolute border-radius-0 border-silver me-3" >
                                                <img src="<?php print $logo; ?>" class="js-logo" />
                                                <input type="hidden" class="mw_option_field" name="logo" id="logo-preview" value="<?php print $logo; ?>" option-group="website"/>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" id="js-upload-logo-image"><?php _e("Upload logo"); ?></button>
                                             <span class="tip mw-btn-icon" id="remove-logo-btn"   data-bs-toggle="tooltip" aria-label="Clear logo" data-bs-original-title="Clear logo">
                                                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/trash.svg" alt="">

                                             </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $favicon_image = get_option('favicon_image', 'website');
                                        $nofavicon = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.svg';
                                        if (!$favicon_image) {
                                            $favicon_image = $nofavicon;
                                        }
                                        ?>

                                        <script>
                                            $(document).ready(function () {
                                                favUP = mw.uploader({
                                                    element: document.getElementById('upload-icoimage'),
                                                    filetypes: 'images',
                                                    multiple: false
                                                });

                                                $(favUP).on('FileUploaded', function (a, b) {
                                                    mw.$("#favicon_image").val(b.src).trigger('change');
                                                    mw.$(".js-icoimage").attr('src', b.src);
                                                    mw.$("link[rel*='icon']").attr('href', b.src);
                                                });

                                                mw.element('#remove-favicon-btn').on('click', function(){
                                                    mw.element('#favicon_image').val('').trigger('change')
                                                    mw.element('.js-icoimage').attr('src', '<?php print $nofavicon; ?>');
                                                })
                                            });
                                        </script>

                                        <br>

                                        <label class="form-label"><?php _e("Website Favicon"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Select an icon for your website.'); ?></small>
                                        <div class="d-flex">
                                            <div class="avatar img-absolute w-40 border-radius-0 border-silver me-3" >
                                                <img src="<?php print $favicon_image; ?>" class="js-icoimage"/>
                                                <input type="hidden" class="mw_option_field" name="favicon_image" id="favicon_image" value="<?php print $favicon_image; ?>" option-group="website"/>
                                            </div>

                                            <button type="button" class="btn btn-outline-primary" id="upload-icoimage"><?php _e("Upload favicon"); ?></button>
                                            <span class="tip mw-btn-icon" id="remove-favicon-btn" data-bs-toggle="tooltip" aria-label="Clear logo" data-bs-original-title="Clear logo">
                                               <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/trash.svg" alt="">
                                            </span>


                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="display: none;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Fonts"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Select fonts you want to install for your website.'); ?></small>

                                        <div class="table-responsive">
                                            <?php
                                            $fonts = get_option('fonts', 'website');

                                            if (!$fonts) {
                                                ?>
                                                <p class="text-muted">No fonts</p>
                                                <?php
                                            } else {
                                                $fonts = json_encode($fonts);
                                                ?>
                                                <table class="table">
                                                    <?php foreach ($fonts as $font) { ?>
                                                        <tr>
                                                            <td><?php print $font['name']; ?></td>
                                                            <td><?php print $font['status']; ?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-7">
        <div class="card-body">


            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e('Social Networks links'); ?></h5>
                    <small class="text-muted"><?php _e('Add links to your social media accounts. Once set up, you can use them anywhere on your site using the "social networks" module with drag and drop technology.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div id="mw-global-fields-social-profile-set">
                            <module type="social_links/admin" module-id="website" live_edit="false"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-7">
        <div class="card-body">


            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e('Online Shop'); ?></h5>
                    <small class="text-muted"><?php _e('Enable or disable your online shop'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                           <div class="row">
                               <module type="shop/orders/settings/enable_disable_shop"/>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mb-7">
        <div class="card-body">


            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Maintenance mode"); ?></h5>
                    <small class="text-muted"><?php _e('Enable or disable maintenance mode on your site'); ?></small>

                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                           <div class="row">
                               <module type="settings/group/maintenance_mode"/>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php event_trigger('mw.admin.settings.website'); ?>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.js-permalink-edit-option-hook', function () {

            mw.clear_cache();

            mw.notification.success("Permalink changes updated.");
        });
    });
</script>
