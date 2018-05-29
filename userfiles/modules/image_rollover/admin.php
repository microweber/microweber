<?php only_admin_access(); ?>

<style>

    #module-image-rollover-settings,
    #module-image-rollover-settings * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #font-and-text {
        width: 100%;
    }

    #font-and-text, #font-and-text * {
        vertical-align: middle;
    }

    .image-row,
    .image-row * {
        vertical-align: middle;
    }

    .the-image-holder {
        width: 200px;
    }

    .the-image, .the-image-rollover {
        margin-right: 12px;
        max-width: 170px;
        max-height: 110px;
        background-color: #eee;
    }

    .the-image[src=''], .the-image-rollover[src=''] {
        width: 133px;
        height: 100px;
        /* background: #eee url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAWCAYAAADXYyzPAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAM5SURBVEiJnZbfax1FFMc/Z/Ymt8WC1Zo29JcSEaTQYNRkz+TBPpTGXvGlDwoqSOsf4N/QP6Q+GFB8qaDoi0JB5IaZRAWpBbFSa00F0ZikDQnRZI8Pd+9l7969yeK+7MycM9/PzNlzZlbMjLpPmqaHnHPvgo1WTRMRcr0PQgi399JytamAc+5lYCgUeobzaZome2k1ip25uQvy8OHGi2Y2mg+thxB+APDevwScrYpQF1owjYvIa8BHw8BSFPJeUzNaJZ9PRNwGZG/tE97SOIB8ZWY/5UObIYTVPrD3/gRw0cxO1dgNwAPgvojcKdiOARPA4zm0vKAM+MU5d31hYWFTVP0RsCtmdqgGdBu4AXwbQtgp+09PT0uSJGdE5IKZHR6it7y7a+83IHvDjC50BWgDT4nIZAn6J51sXQNQ1THgZEF3dWlp6S5wa2Zm5o5z7k2gF8HCJk46J62GGU/kth0zm48xrgPfea8HzXgmt/1jZh/GGNdmZ/VAlvEKcBaQorD3/n6WZZ8uLi7+oarzwBXgeEXkni+Wk4Ec7HWMZsF2I8a4qqrNLOMyMFmGdubYCRF5R1WPhxD+Ba6LSFaRI311POKcXFbVlqq+DZzOx9fM7Ju8/SowXhQoQLtDTeCSqiYhhBUzWy5DRQrgvCwOACmd7Ow6/RZj3EnTtAk8Owi1qnIaE5En8/b3ZShIB7xPLW7lPqcpHDhDyqy4ibG8u1GGmhmN4dBeCLvGhPy71oBCp26r9ABw+0ABHsnb94CdYVCRgcj9lb+bFXkweElUOJ2amnpBYoybInLLrBpaOqn+TpLkbq43WYYOgIcky6PN5shzAFmWfQms7QM14ON2u23e+3Gwiaqo9mX1Ht+tpaqHY4wbwHvAj0OgvwPXQgjL+RoumVVHVdI0vVozWdbzk20FQFWPicjTZvYYnTP8dgjhV4Bz59Rtb/M6pfIr6omqXq0B7T5bwBdJMnKz3f564JIA8N5PmNlF4OgeequSpuksMNfvABXXWm8y2AMzfqZzqdwDjgBHRWTCzMYH5/Tp7QLzYmao6nnAA40a0LrRqYJuA5+FEG72/kBUdQpoichoDYH/s6At4PPur9R/ibnHN/zDO4wAAAAASUVORK5CYII=) no-repeat center; */
    }

    #sizeslider,
    #fontsizeslider {
        width: 135px;
    }

    #module-image-rollover-settings .mw-ui-box-content {
        padding: 20px;
    }

</style>

<?php
$default_image = get_option('default-image', $params['id']);
$rollover_image = get_option('rollover-image', $params['id']);
$text = get_option('text', $params['id']);
$size = get_option('size', $params['id']);
if ($size == false or $size == '') {
    $size = 60;
}
?>

<div class="module-live-edit-settings" id="module-image-rollover-settings">

    <div class="mw-ui-field-holder">

        <div class="mw-ui-box mw-ui-box-content">
            <div class="mw-ui-row-nodrop image-row">

                <div class="mw-ui-col">

                    <h3>Default image</h3>
                    <img src="<?php print $default_image; ?>" class="pull-left the-image"
                         alt="" <?php if ($default_image != '' and $default_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                    <br>
                    <div style="padding-top: 15px; clear: both"></div>
                    <span class="mw-ui-btn" id="upload-image"><span
                                class="mw-icon-upload"></span><?php _e('Upload Image'); ?></span><br/>
                    <?php _e('or'); ?> <a href="javascript:mw_admin_image_rollover_upload_browse_existing()" class="mw-ui-link mw-ui-btn-small"><?php _e('browse uploaded'); ?></a>

                </div>

                <div class="mw-ui-col">

                    <h3>Rollover image</h3>
                    <img src="<?php print $rollover_image; ?>" class="pull-left the-image-rollover"
                         alt="" <?php if ($rollover_image != '' and $rollover_image != false) { ?><?php } else { ?> style="display:block;" <?php } ?> />
                    <br>
                    <div style="padding-top: 15px; clear: both"></div>
                    <span class="mw-ui-btn" id="upload-image-rollover"><span
                                class="mw-icon-upload"></span><?php _e('Upload Image'); ?></span><br/>
                    <?php _e('or'); ?> <a href="javascript:mw_admin_image_rollover_upload_browse_existing(true)" class="mw-ui-link mw-ui-btn-small"><?php _e('browse uploaded'); ?></a>

                </div>
			</div>

			<div class="mw-ui-row-nodrop image-row">

                <div class="mw-ui-col">

                    <hr/>

                    <label class="mw-ui-label" style="padding-top: 10px;"><span><?php _e('Image size'); ?></span> - <b id="imagesizeval"></b></label>
                    <div id="sizeslider" class="mw-slider"></div>
                    <br>
                    <label class="mw-ui-check"><input type="checkbox" checked="" id="size_auto"
                                                      value="pending"><span></span><span><?php _e('Auto'); ?></span></label>


					<div class="mw-ui-col-container" style="padding-top: 20px;">

						<div class="mw-ui-field-holder" style="padding-bottom: 20px;">
							<label class="mw-ui-label">Title:</label>
							<input type="text"
								  class="mw-ui-field mw-ui-filed-big mw_option_field w100"
								  placeholder="<?php _e('Enter title'); ?>"
								  value="<?php print $text; ?>"
								  name="text"
								  id="text"/>
						</div>

						<div class="mw-ui-field-holder" style="padding-bottom: 20px;">
							<label class="mw-ui-label">Links to:</label>
							<input type="text"
								  class="mw-ui-field mw-ui-filed-big mw_option_field w100"
								  placeholder="<?php _e('Enter URL'); ?>"
								  value="<?php print $text; ?>"
								  name="href-url"
								  id="href-url"/>
						</div>

						<module type="admin/modules/templates" simple=true/>
					</div>

                </div>

             </div>

                <input type="hidden" class="mw_option_field" name="size" id="size"/>
                <script>
                    $(function () {
                        $("#sizeslider").slider({
                            change: function (event, ui) {
                                $('#size').val(ui.value).trigger('change');
                                $("#size_auto").attr("checked", false);
                            },
                            slide: function (event, ui) {
                                $("#imagesizeval").html(ui.value + "px");
                            },
                            min: 30,
                            max: 320,
                            value:<?php if ($size != 'auto') {
                            print $size;
                        } else {
                            print 60;
                        } ?>
                        });

                        if ("<?php print $size; ?>" == 'auto') {
                            $("#imagesizeval").html('auto');
                            $("#size_auto").attr("checked", true);
                        }
                        else {
                            $("#imagesizeval").html("<?php print $size; ?>px");
                            $("#size_auto").attr("checked", false);
                        }


                        $("#size_auto").bind('change', function () {
                            if (this.checked === true) {
                                setAuto()
                            }
                            else {
                                var val1 = $('#sizeslider').slider("option", "value");
                                $('#size').val(val1).trigger('change');
                                $("#imagesizeval").html(val1 + 'px');
                            }
                        });

                        setAuto = function () {
                            $('#size').val('auto').trigger('change');
                            $("#imagesizeval").html('auto');
                        };
                    });
                </script>

        </div>
    </div>
    <input type="hidden" class="mw_option_field" name="default-image" id="default-image"/>
    <input type="hidden" class="mw_option_field" name="rollover-image" id="rollover-image"/>
</div>


<script>

    $(document).ready(function () {
        UP = mw.uploader({
            element: mwd.getElementById('upload-image'),
            filetypes: 'images',
            multiple: false
        });

        $(UP).bind('FileUploaded', function (a, b) {
            setNewImage(b.src);
            setAuto();
        });

        UPRollover = mw.uploader({
            element: mwd.getElementById('upload-image-rollover'),
            filetypes: 'images',
            multiple: false
        });

        $(UPRollover).bind('FileUploaded', function (a, b) {
            setNewImageRollover(b.src);
            setAuto();
        });

    });

    function setNewImage(s) {
        mw.$("#default-image").val(s).trigger('change');
        mw.$(".the-image").show().attr('src', s);
    }

    function setNewImageRollover(s) {
        mw.$("#rollover-image").val(s).trigger('change');
        mw.$(".the-image-rollover").show().attr('src', s);
    }

    var mw_admin_image_rollover_upload_browse_existing = function (rollover=false) {

        var mw_admin_image_rollover_upload_browse_existing_modal = window.top.mw.modalFrame({
            url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_image_rollover_upload_browse_existing_modal<?php print $params['id'] ?>&from_url=<?php print site_url() ?>',
            title: "Browse pictures",
            id: 'mw_admin_image_rollover_upload_browse_existing_modal<?php print $params['id'] ?>',
            onload: function () {
                this.iframe.contentWindow.mw.on.hashParam('select-file', function () {
                    mw_admin_image_rollover_upload_browse_existing_modal.hide();
                    mw.notification.success('<?php _e('Image selected') ?>');
                    if(rollover) {
                    	setNewImageRollover(this);
                    } else {
                    	setNewImage(this);
                    }
                })
                this.iframe.contentWindow.document.body.style.padding = '15px';
            },
            height: 400
        })

    }
</script>