<?php only_admin_access(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.js-permalink-edit-option-hook', function () {

            mw.clear_cache();

            mw.notification.success("Permalink changes updated.");
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0">
        <div class="card-header">
            <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>General</strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Seo Settings</h5>
                    <small class="text-muted">Fill in the fields for maximum results when finding your website in search engines.</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e("Website Name"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("This is very important for search engines.") . ' '; ?><?php _e("Your website will be categorized by many criteria and its name is one of them."); ?></small>
                                        <input name="website_title" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option('website_title', 'website'); ?>"/>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e("Website Description"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Describe what your website is about"); ?>.</small>
                                        <textarea name="website_description" class="mw_option_field form-control" rows="7" type="text" option-group="website"><?php print get_option('website_description', 'website'); ?></textarea>
                                    </div>

                                    <?php
                                    /*        <div class="form-group">
                                                <label class="control-label">
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
                                        <label class="control-label"><?php _e("Website Keywords"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Ex.: Cat, Videos of Cats, Funny Cats, Cat Pictures, Cat for Sale, Cat Products and Food"); ?></small>
                                        <input name="website_keywords" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option('website_keywords', 'website'); ?>"/>
                                    </div>

                                    <div class="form-group js-permalink-edit-option-hook">
                                        <label class="control-label"><?php _e("Permalink Settings"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Choose the URL posts & page format."); ?></small>
                                        <?php $permalinkStructures = mw()->permalink_manager->getStructures(); ?>
                                        <?php $currentPremalinkStructure = get_option('permalink_structure', 'website'); ?>


                                        <div class="d-block d-xl-flex align-items-center">
                                            <small class="mr-2 my-2 font-weight-bold"><?php echo mw()->url_manager->site_url(); ?> </small>
                                            <select name="permalink_structure" class="selectpicker mw_option_field" data-width="100%" data-style="btn-sm" option-group="website">
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

    <div class="card bg-none style-1 mb-0">
        <div class="card-body pt-3">
            <hr class="thin mt-0 mb-5">

            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold"><?php echo _e('General Settings'); ?></h5>
                    <small class="text-muted"><?php echo _e('Set regional settings for your website or online store. They will also affect the language you use and the fees for the orders.'); ?></small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e("Date Format"); ?></label>
                                        <small class="text-muted d-block mb-2">Choose a date format for your website</small>
                                        <?php $date_formats = array("Y-m-d H:i:s", "Y-m-d H:i", "d-m-Y H:i:s", "d-m-Y H:i", "m/d/y", "m/d/Y", "d/m/Y", "F j, Y g:i a", "F j, Y", "F, Y", "l, F jS, Y", "M j, Y @ G:i", "Y/m/d \a\t g:i A", "Y/m/d \a\t g:ia", "Y/m/d g:i:s A", "Y/m/d", "g:i a", "g:i:s a", 'D-M-Y', 'D-M-Y H:i'); ?>
                                        <?php $curent_val = get_option('date_format', 'website'); ?>
                                        <select name="date_format" class="selectpicker mw_option_field" data-width="100%" data-size="7" option-group="website">
                                            <?php if (is_array($date_formats)): ?>
                                                <?php foreach ($date_formats as $item): ?>
                                                    <option value="<?php print $item ?>" <?php if ($curent_val == $item): ?> selected="selected" <?php endif; ?>><?php print date($item, time()) ?> - (<?php print $item ?>)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Time Zone"); ?></label>
                                        <small class="text-muted d-block mb-2">Set a time zone</small>
                                        <?php $curent_time_zone = get_option('time_zone', 'website'); ?>
                                        <?php
                                        if ($curent_time_zone == false) {
                                            $curent_time_zone = date_default_timezone_get();
                                        }

                                        $timezones = timezone_identifiers_list(); ?>
                                        <select name="time_zone" class="selectpicker mw_option_field" data-width="100%" data-size="7" data-live-search="true" option-group="website">
                                            <?php foreach ($timezones as $timezone) {
                                                echo '<option';
                                                if ($timezone == $curent_time_zone) echo ' selected="selected"';
                                                echo '>' . $timezone . '</option>' . "\n";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $favicon_image = get_option('favicon_image', 'website');
                                        if (!$favicon_image) {
                                            $favicon_image = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image-2.jpg';
                                        }
                                        ?>

                                        <script>
                                            $(document).ready(function () {
                                                favUP = mw.uploader({
                                                    element: mwd.getElementById('upload-icoimage'),
                                                    filetypes: 'images',
                                                    multiple: false
                                                });

                                                $(favUP).bind('FileUploaded', function (a, b) {
                                                    mw.$("#favicon_image").val(b.src).trigger('change');
                                                    mw.$(".js-icoimage").attr('src', b.src);
                                                    mw.$("link[rel*='icon']").attr('href', b.src);
                                                });
                                            });
                                        </script>

                                        <label class="control-label"><?php _e("Change Favicon"); ?></label>
                                        <small class="text-muted d-block mb-2">Select an icon for your website. It is best to be part of your logo.</small>
                                        <div class="d-flex">
                                            <div class="img-circle-holder img-absolute w-40 border-radius-0 border-silver mr-3">
                                                <img src="<?php print $favicon_image; ?>" class="js-icoimage"/>
                                                <input type="hidden" class="mw_option_field" name="favicon_image" id="favicon_image" value="<?php print $favicon_image; ?>" option-group="website"/>
                                            </div>

                                            <button type="button" class="btn btn-outline-primary" id="upload-icoimage"><?php _e("Upload favicon"); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Posts per Page"); ?></label>
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
                            </div>

                            <div class="row" style="display: none;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Fonts"); ?></label>
                                        <small class="text-muted d-block mb-2">Select fonts you want to install for your website.</small>

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

    <div class="card bg-none style-1 mb-0">
        <div class="card-body pt-3">
            <hr class="thin mt-0 mb-5">

            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Social Networks links</h5>
                    <small class="text-muted">Add links to your social media accounts. Once set up, you can use them anywhere on your site using the "social networks" module with drag and drop technology.</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12 socials-settings">
                                    <div class="form-group mb-4">
                                        <label class="control-label">Select and type socials links you want to show</label>
                                        <small class="text-muted d-block mb-2">Select the social networks you want to see on your site, blog and store</small>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox d-flex align-items-center">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-facebook mdi-20px lh-1_0 mr-2"></i> facebook.com/</label>
                                            <input class="form-control" value="Microweber">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox d-flex align-items-center">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-twitter mdi-20px lh-1_0 mr-2"></i> twitter.com/</label>
                                            <input class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox d-flex align-items-center">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-pinterest mdi-20px lh-1_0 mr-2"></i> pinterest.com/</label>
                                            <input class="form-control" value="">
                                        </div>
                                    </div>

                                    <a href="javascript:;" class="btn btn-outline-primary btn-sm mb-3" data-toggle="collapse" data-target="#more-socials-settings" aria-expanded="true">Show more</a>

                                    <div class="collapse" id="more-socials-settings">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-youtube mdi-20px lh-1_0 mr-2"></i> youtube.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-linkedin mdi-20px lh-1_0 mr-2"></i> linkedin.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-github mdi-20px lh-1_0 mr-2"></i> github.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-instagram mdi-20px lh-1_0 mr-2"></i> instagram.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-soundcloud mdi-20px lh-1_0 mr-2"></i> soundcloud.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-mixdcloud mdi-20px lh-1_0 mr-2"></i> mixdcloud.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-medium mdi-20px lh-1_0 mr-2"></i> medium.com/</label>
                                                <input class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-rss mdi-20px lh-1_0 mr-2"></i> RSS</label>
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
    </div>
</div>



