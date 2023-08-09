<?php

only_admin_access();
?>



<script src="<?php print $config['url_to_module']; ?>js/rte_css_editor2.js"></script>
<script>
    var colorPickers = [];


</script>



<div id="domtree"></div>

<style>

    #css-editor-root .mw-accordion-title svg{
        width:21px;
        height: 21px;
        margin-inline-end: 8px;
    }
    #css-editor-root .mw-accordion-title{
        font-weight: bold;
    }

    #css-editor-root #columns-edit .mw-field{
        padding-bottom: 15px;
    }
    #css-editor-root #columns-edit .mdi{
        font-size: 19px;
        position: relative;
        top: 4px;
        margin-inline-end: 15px;
        margin-inline-start: 15px;
    }

     .default-values-list > span{
        display:block;
        padding:5px 10px;
        cursor: pointer;
    }
      .default-values-list{
        position: absolute;
        top:-100%;
        left:-100%;
        padding: 10px;
        z-index:1;
        background: #fff;
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

    }
    #css-editor-root .animation-clear-btn{
        float: right;
        margin: -30px 0 0 0;
    }

    html[dir="rtl"] #css-editor-root  .animation-clear-btn{
        float: left;
     }

    #css-editor-root .mw-field .mw-range + .mw-range{
        display: none;
    }

</style>






<style>

    <?php include "style.css";  ?>
    <?php
        if (_lang_is_rtl()) {
            include "rtl.css";
        }
    ?>

</style>
<div id="css-editor-root">

  <div data-mwcomponent="accordion" class="mw-ui-box mw-accordion" data-options="openFirst: false">




<mw-accordion-item class="mw-accordion-item-css">
    <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/background.svg"> <?php _e("Background"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label><?php _e("Image"); ?></label>
            <div class="s-field-content">
            <div class="mw-ui-btn-nav" id="background-image-nav">

                <span
                    class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px" data-tip="Select background image"
                    id="background-select-item"><span class="background-preview"></span></span>

                <span
                    class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px" data-tip="Select gradient"
                    id="background-select-gradient" style="display: none"><span class="background-gradient"></span></span>


                <span id="background-remove" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Remove background" data-tipposition="top-right"><span class="mdi mdi-delete"></span></span>
                <span id="background-reset" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Reset background" data-tipposition="top-right"><span class="mdi mdi-history"></span></span>
            </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Color"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                    <input type="text" class="colorField unit" data-prop="backgroundColor">
                </div>
            </div>
        </div>

        <div class="s-field">
            <label><?php _e("Size"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundSize">
                        <option value="auto"><?php _e("Auto"); ?></option>
                        <option value="contain"><?php _e("Fit"); ?></option>
                        <option value="cover"><?php _e("Cover"); ?></option>
                        <option value="100% 100%"><?php _e("Scale"); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Repeat"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" class="regular" data-prop="backgroundRepeat">
                        <option value="repeat"><?php _e("repeat"); ?></option>
                        <option value="no-repeat"><?php _e("no-repeat"); ?></option>
                        <option value="repeat-x"><?php _e("repeat horizontally"); ?></option>
                        <option value="repeat-y"><?php _e("repeat vertically "); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field" id="text-mask">
            <label>Text mask</label>
            <script>
                mask = function (val) {
                    var $node = $(ActiveNode);
                    var action = val ? 'addClass' : 'removeClass';
                    $node[action]('mw-bg-mask');
                    if (action === 'addClass') {
                        output('color', 'transparent')
                    } else {
                        output('color', '')
                    }
                    mw.top().app.registerChange($node[0]);
                }
            </script>
            <div class="s-field-content">
                <label class="mw-ui-check">
                    <input type="checkbox" id="text-mask-field"  onchange="mask(this.checked)">
                    <span></span><span></span>
                </label>
            </div>
        </div>

        <div class="s-field">
            <label><?php _e("Position"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select class="regular" data-prop="backgroundPosition">
                        <option value="0% 0%"><?php _e("Left Top"); ?></option>
                        <option value="50% 0%"><?php _e("Center Top"); ?></option>
                        <option value="100% 0%"><?php _e("Right Top"); ?></option>

                        <option value="0% 50%"><?php _e("Left Center"); ?></option>
                        <option value="50% 50%"><?php _e("Center Center"); ?></option>
                        <option value="100% 50%"><?php _e("Right Center"); ?></option>

                        <option value="0% 100%"><?php _e("Left Bottom"); ?></option>
                        <option value="50% 100%"><?php _e("Center Bottom"); ?></option>
                        <option value="100% 100%"><?php _e("Right Bottom"); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</mw-accordion-item>

        <mw-accordion-item class="mw-accordion-item-css">

            <?php $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();


            $enabled_custom_fonts_array = array();

            if (is_string($enabled_custom_fonts) and $enabled_custom_fonts) {
                $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
            }

            ?>


            <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/typography.svg"> <?php _e("Typography"); ?></div>
            <div class="mw-accordion-content mw-ui-box-content css-gui-element-typography">

                <div class="s-field">
                    <label><?php _e("Font Family"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontFamily">
                                    <option value="inherit">Default</option>

                                    <?php if($enabled_custom_fonts_array): ?>
                                    <?php foreach ($enabled_custom_fonts_array as $font): ?>
                                        <option value='<?php print $font; ?>'><?php print $font; ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="s-field">
                    <label><?php _e("Text align"); ?></label>
                    <div class="s-field-content">
                        <div class="text-align">
                            <span class="ta-left" data-value="left"><span class="mdi mdi-format-align-left"></span></span>
                            <span class="ta-center" data-value="center"><span class="mdi mdi-format-align-center"></span></span>
                            <span class="ta-right" data-value="right"><span class="mdi mdi-format-align-right"></span></span>
                            <span class="ta-justify" data-value="justify"><span class="mdi mdi-format-align-justify"></span></span>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Size"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="fontSize" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Line height"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="lineHeight" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Color"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                                <input type="text" class="colorField unit" data-prop="color">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Style"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontStyle">
                                    <option value="normal"><?php _e("normal"); ?></option>
                                    <option value="italic"><?php _e("italic"); ?></option>
                                    <option value="oblique"><?php _e("oblique"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Weight"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="fontWeight">
                                    <option value="normal"><?php _e("normal"); ?></option>
                                    <option value="bold"><?php _e("bold"); ?></option>
                                    <option value="bolder"><?php _e("bolder"); ?></option>
                                    <option value="lighter"><?php _e("lighter"); ?></option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                    <option value="500">500</option>
                                    <option value="600">600</option>
                                    <option value="700">700</option>
                                    <option value="800">800</option>
                                    <option value="900">900</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Text transform"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat" data-size="medium">
                                <select class="regular" data-prop="textTransform">
                                    <option value="none"><?php _e("none"); ?></option>
                                    <option value="capitalize"><?php _e("capitalize"); ?></option>
                                    <option value="uppercase"><?php _e("uppercase"); ?></option>
                                    <option value="lowercase"><?php _e("lowercase"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Word Spacing"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="wordSpacing" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="s-field">
                    <label><?php _e("Letter Spacing"); ?></label>
                    <div class="s-field-content">
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="letterSpacing" data-size="medium"><input type="text"></div>
                        </div>
                    </div>
                </div>


            </div>
        </mw-accordion-item>

        <mw-accordion-item class="mw-accordion-item-css" id="overlay-edit">
        <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/overlay.svg"> <?php _e("Overlay"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="s-field">
                <label><?php _e("Color"); ?></label>
                <div class="s-field-content">
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                        <input type="text" class="colorField unit" id="overlay-color" data-prop="overlay-color">
                    </div>
                </div>
            </div>
            <div class="s-field">
                <label><?php _e("Blend mode"); ?></label>
                <div class="s-field-content">
                    <div class="mw-field mw-field-flat" data-size="medium">

                        <select data-prop="overlay-blend-mode" id="overlay-blend-mode" class="regular">
                            <option value='normal' selected><?php _e('None'); ?></option>
                            <option value='multiply'>multiply</option>
                            <option value='screen'>screen</option>
                            <option value='overlay'>overlay</option>
                            <option value='darken'>darken</option>
                            <option value='lighten'>lighten</option>
                            <option value='color-dodge'>color-dodge</option>
                            <option value='color-burn'>color-burn</option>
                            <option value='difference'>difference</option>
                            <option value='exclusion'>exclusion</option>
                            <option value='hue'>hue</option>
                            <option value='saturation'>saturation</option>
                            <option value='color'>color</option>
                            <option value='luminosity'>luminosity</option>

                        </select>
                    </div>
                </div>
            </div>
        </div>
    </mw-accordion-item>
        <mw-accordion-item class="mw-accordion-item-css" id="container-type">
        <div class="mw-ui-box-header mw-accordion-title">
            <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/container.svg"> <?php _e("Container"); ?>
        </div>
        <div class="mw-accordion-content mw-ui-box-content">

        <div class="s-field" id="field-conatiner-type">
            <label><?php _e("Container type"); ?></label>
            <div class="s-field-content">
                <label class="mw-ui-check"> <input type="radio" onchange="sccontainertype(this.value)" name="containertype" value="container"/> <span></span><span> Fixed </span> </label>
                <label class="mw-ui-check"> <input type="radio" onchange="sccontainertype(this.value)" name="containertype" value="container-fluid"/> <span></span><span> Fluid </span> </label>

            </div>
        </div>
        </div>
    </mw-accordion-item>
        <mw-accordion-item class="mw-accordion-item-css" id="columns-edit">

        <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/grid.svg"> <?php _e("Grid"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">

            <div class="s-field">

                <div class="s-field-content">
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Desktop'); ?></label>
                        <i class=" mdi mdi-monitor"></i>
                        <select data-prop="col-desktop" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Tablet'); ?></label>
                        <i class=" mdi mdi-tablet"></i>
                        <select data-prop="col-tablet" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mw-field mw-field-flat" data-size="medium">
                        <label><?php _e('Mobile'); ?></label>
                        <i class=" mdi mdi-cellphone"></i>
                        <select data-prop="col-mobile" class="regular">
                            <option value='' selected disabled><?php _e('Choose'); ?></option>
                            <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
                                <option value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </div>

        </div>


    </mw-accordion-item>
        <mw-accordion-item class="mw-accordion-item-css"  id="size-box" style="display: none">
        <div class="mw-ui-box-header mw-accordion-title"><?php _e("Size"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="mw-esr-col">
                <div class="mw-esc">
                    <label><?php _e("Width"); ?></label>
                    <div class="mw-multiple-fields">
                        <div
                            class="mw-field mw-field-flat unit"
                            data-prop="width"
                            data-size="medium"
                            >
                            <input type="text" data-options="min: 50, max: 2000">
                        </div>
                        <span class="btn btn-link" onclick="output('width', 'auto')">Auto</span>
                    </div>
                </div>
                <div class="mw-esc">
                    <label><?php _e("Height"); ?></label>
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat unit" data-prop="height" data-size="medium">
                            <input type="text" data-options="min: 50, max: 2000">

                        </div>
                        <span class="btn btn-link" onclick="output('height', 'auto')">Auto</span>

                    </div>
                </div>
            </div>
            <div class="size-advanced" style="display: none;">
                <div class="mw-esr-col">
                    <div class="mw-esc">
                        <label><?php _e("Min Width"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="minWidth" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="btn btn-link" onclick="output('minWidth', '0')">None</span>

                        </div>
                    </div>
                    <div class="mw-esc">
                        <label><?php _e("Min Height"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="minHeight" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="btn btn-link" onclick="output('minHeight', '0')">None</span>
                        </div>
                    </div>

                </div>
                <div class="mw-esr-col">
                    <div class="mw-esc">
                        <label><?php _e("Max Width"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="maxWidth" data-size="medium"><input type="text" data-options="min: 50, max: 2000"></div>
                            <span class="btn btn-link" onclick="output('maxWidth', 'none')">None</span>
                        </div>

                    </div>
                    <div class="mw-esc">
                        <label><?php _e("Max Height"); ?></label>
                        <div class="mw-multiple-fields">
                            <div class="mw-field mw-field-flat unit" data-prop="maxHeight" data-size="medium"><input type="text"></div>
                            <span class="btn btn-link" onclick="output('maxHeight', 'none')">None</span>
                        </div>
                    </div>
                </div>
            </div>
            <span class="mw-ui-link" onclick="mw.$('.size-advanced').slideToggle()">Advanced</span>
        </div>
    </mw-accordion-item>

        <mw-accordion-item class="mw-accordion-item-css" >
        <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/spacing.svg"><?php _e("Spacing"); ?></div>
        <div class="mw-accordion-content mw-ui-box-content">
            <div class="mw-element-spacing-editor">
                <span class="mw-ese-label"><?php _e("Margin"); ?></span>
                <div class="mw-ese-holder mw-ese-margin">
                    <span class="input mw-ese-top"><input type="text" class=" margin-top"></span>
                    <span class="input mw-ese-right"><input type="text" class=" margin-right"></span>
                    <span class="input mw-ese-bottom"><input type="text" class=" margin-bottom"></span>
                    <span class="input mw-ese-left"><input type="text" class=" margin-left"></span>
                    <div class="mw-ese-holder mw-ese-padding">
                        <span class="input mw-ese-top"><input type="text" min="0" class=" padding-top"></span>
                        <span class="input mw-ese-right"><input type="text" min="0" class=" padding-right"></span>
                        <span class="input mw-ese-bottom"><input type="text" min="0" class=" padding-bottom"></span>
                        <span class="input mw-ese-left"><input type="text" min="0" class=" padding-left"></span>
                        <span class="mw-ese-label"><?php _e("Padding"); ?></span>
                    </div>
                </div>

            </div>
        </div>
    </mw-accordion-item>


        <mw-accordion-item class="mw-accordion-item-css"  >
    <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/border.svg"><?php _e("Border"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="s-field">
            <label><?php _e("Position"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" id="border-position">
                        <option value="all" selected><?php _e("All"); ?></option>
                        <option value="Top"><?php _e("Top"); ?></option>
                        <option value="Right"><?php _e("Right"); ?></option>
                        <option value="Bottom"><?php _e("Bottom"); ?></option>
                        <option value="Left"><?php _e("Left"); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Size"); ?></label>
            <div class="s-field-content">
                <div class="mw-multiple-fields">
                    <div class="mw-field mw-field-flat" data-size="medium"><input type="text" id="border-size"></div>
                </div>
            </div>
        </div>
        <div class="s-field">
            <label><?php _e("Color"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>
                    <input type="text" placeholder="#ffffff" class="colorField unit" data-position="top-right" id="border-color">
                </div>

            </div>
        </div>
        <div class="s-field">
            <label>Type</label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <select type="text" id="border-type">
                        <option value="" disabled selected><?php _e("Choose"); ?></option>
                        <option value="none"><?php _e("none"); ?></option>
                        <option value="solid"><?php _e("solid"); ?></option>
                        <option value="dotted"><?php _e("dotted"); ?></option>
                        <option value="dashed"><?php _e("dashed"); ?></option>
                        <option value="double"><?php _e("double"); ?></option>
                        <option value="groove"><?php _e("groove"); ?></option>
                        <option value="ridge"><?php _e("ridge"); ?></option>
                        <option value="inset"><?php _e("inset"); ?></option>
                        <option value="outset"><?php _e("outset"); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</mw-accordion-item>
        <mw-accordion-item class="mw-accordion-item-css" >
    <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/miscellaneous.svg"><?php _e("Miscellaneous"); ?></div>
    <div class="mw-accordion-content mw-ui-box-content">
        <div class="rouded-corners" >
            <label><?php _e("Rounded Corners"); ?></label>
            <div class="s-field-content">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <input type="text" class="regular order-1" data-prop="borderTopLeftRadius">
                            <span class="mw-field mw-field-flat-prepend order-2"><i class="angle angle-top-left"></i></span>
                        </div>
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <span class="mw-field mw-field-flat-prepend"><i class="angle angle-top-right"></i></span>
                            <input class="regular" type="text" data-prop="borderTopRightRadius">
                        </div>
                    </div>
                </div>
                <div class="mw-field mw-field-flat" data-size="medium">
                    <div class="mw-multiple-fields">
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <input class="regular order-1" type="text" data-prop="borderBottomLeftRadius">
                            <span class="mw-field mw-field-flat-prepend order-2"><i class="angle angle-bottom-left"></i></span>
                        </div>
                        <div class="mw-field mw-field-flat" data-size="medium">
                            <span class="mw-field mw-field-flat-prepend"><i class="angle angle-bottom-right"></i></span>
                            <input class="regular" type="text" data-prop="borderBottomRightRadius">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</mw-accordion-item>
        <mw-accordion-item class="mw-accordion-item-css" id="classtags-accordion">

            <div class="mw-ui-box-header mw-accordion-title"> <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/attributes.svg"><?php _e("Attributes"); ?></div>
            <div class="mw-accordion-content mw-ui-box-content">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Classes"); ?></label>
                    <div class="form-select  w100" id="classtags"></div>
                </div>

            </div>

        </mw-accordion-item>

        <mw-accordion-item id="animations-accordion">

            <style>
                #css-editor-root #animations {
                    display: block;
                }
            </style>



            <div class="mw-ui-box-header mw-accordion-title">
            <img class="rte_css_editor_svg svg" width="20px" src="<?php print mw_includes_url(); ?>img/animations.svg"><?php _e("Animations"); ?></div>
            <div class="mw-accordion-content mw-ui-box-content">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Animations"); ?></label>


                    <div class="w100" id="animations">

                    </div>
                </div>

            </div>

        </mw-accordion-item>
</div>

<div class="mw-css-editor">

</div>
</div>
