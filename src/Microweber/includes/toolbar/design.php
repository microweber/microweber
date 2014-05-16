
<div id="design_bnav" class="mw-defaults toolbar_bnav" style="width: 160px;">



<div id="design_bnav_handle"><a id="design_close" class="mw_ex_tools" href="#design_bnav" onclick="mw.$('.mw_ex_tools').removeClass('active');"></a></div>

  <ul class="ts_main_ul">

    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_size" href="javascript:;"><?php _e("Size"); ?></a>
      <div class="ts_action" style="width: 190px;top: 0;">
        <div class="ts_action_item"> <span class="ed_label left"><?php _e("Width"); ?></span>
          <div class="ed_slider width-slider es_item left" id="width_slider" data-onstart="width_slider_onstart" data-max="999" data-min="100" data-type="width"></div>
          <span class="slider_val">
          <input type="text" name="width_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
        <div class="ts_action_item"> <span class="ed_label left"><?php _e("Auto"); ?></span>
          <input type="checkbox" class="mwcheck" id="ed_auto_width" />
        </div>
                <div class="ts_action_item"> <span class="ed_label left"><?php _e("Height"); ?></span>
          <div class="ed_slider height-slider es_item left" id="height_slider" data-onstart="" data-max="999" data-min="10" data-type="height"></div>
          <span class="slider_val">
          <input type="text" name="height_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
        <div class="ts_action_item"> <span class="ed_label left"><?php _e("Auto"); ?></span>
          <input type="checkbox" class="mwcheck" id="ed_auto_height" />
        </div>
      </div>

    </li>
    <li class="ts_main_li"> <a class="ts_main_a dd_design_spacing" href="javascript:;"><?php _e("Spacing"); ?></a>
      <div class="ts_action" style="width: 200px;">
        <div class="ts_action_item mw-designtype-universal">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider left" id="margin_selector" data-for="margin_slider"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val"><?php _e("Margin"); ?></span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: 100%">
                <li value="true">
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="margin-top"><?php _e("Top"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" data-value="margin-left"><?php _e("Left"); ?></span></td>
                        <td><span class="square_map_item square_map_item_default active" data-value="margin"><?php _e("All"); ?></span></td>
                        <td><span class="square_map_item" data-value="margin-right"><?php _e("Right"); ?></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="margin-bottom"><?php _e("Bottom"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <span class="square_map_value">&nbsp;</span> </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="ed_slider margin-slider es_item left" id="margin_slider" data-min="0" data-max="100" data-value="0" data-type="margin"></div>
          <span class="slider_val">
          <input type="text" name="margin_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
        <div class="ts_action_item mw-designtype-element">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider left" id="padding_selector" data-for="padding_slider"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val"><?php _e("Padding"); ?></span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: 100%">
                <li value="true">
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="padding-top"><?php _e("Top"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" data-value="padding-left"><?php _e("Left"); ?></span></td>
                        <td><span class="square_map_item square_map_item_default active" data-value="padding"><?php _e("All"); ?></span></td>
                        <td><span class="square_map_item" data-value="padding-right"><?php _e("Right"); ?></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="padding-bottom"><?php _e("Bottom"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <span class="square_map_value">&nbsp;</span> </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="ed_slider padding-slider es_item left" id="padding_slider" data-min="0" data-max="100" data-value="0" data-type="padding"></div>
          <span class="slider_val">
          <input type="text" name="padding_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
      </div>
    </li>
    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_border" href="javascript:;"><?php _e("Border"); ?></a>
      <div class="ts_action" style="width: 185px;">
        <div class="ts_action_item ts_border_position_selector"> <a class="border-style none" data-val="none" title="<?php _e("Remove Borders"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style all active" data-val="border" title="<?php _e("All"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bleft" data-val="borderLeft" title="<?php _e("Border Left"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bright" data-val="borderRight" title="<?php _e("Border Right"); ?>"></a><span class="mw_dlm left"></span> <a class="border-style top" data-val="borderTop" title="<?php _e("Border Top"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bottom" data-val="borderBottom" title="<?php _e("Border Bottom"); ?>"></a> </div>
        <div class="ts_action_item">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg dd_border_selector" style="margin: 0 8px 0 0;width: auto;"  title="<?php _e("Border Style"); ?>" data-value="solid"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val" style="width: 62px;"><span class="border_selector" style="border-bottom-style: solid">Solid</span></span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: auto">
                <li value="solid"><a href="javascript:;"><span class="border_selector" style="border-bottom-style: solid"><?php _e("Solid"); ?></span></a></li>
                <li value="dotted"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dotted"><?php _e("Dotted"); ?></span></a></li>
                <li value="dashed"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dashed"><?php _e("Dashed"); ?></span></a></li>
              </ul>
            </div>
          </div>
          <div class="mw-dropdown mw-dropdown-type-wysiwyg dd_borderwidth_Selector" style="margin-left: -5px;width: auto"  title="<?php _e("Border Width"); ?>" data-value="0"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val" style="width: auto">0</span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: auto">
                <li value="0px"><a href="javascript:;">0</a></li>
                <li value="1px"><a href="javascript:;">1</a></li>
                <li value="2px"><a href="javascript:;">2</a></li>
                <li value="3px"><a href="javascript:;">3</a></li>
                <li value="4px"><a href="javascript:;">4</a></li>
                <li value="5px"><a href="javascript:;">5</a></li>
                <li value="6px"><a href="javascript:;">6</a></li>
                <li value="7px"><a href="javascript:;">7</a></li>
                <li value="8px"><a href="javascript:;">8</a></li>
                <li value="9px"><a href="javascript:;">9</a></li>
              </ul>
            </div>
          </div>
          <span class="slider_val_label left" style="margin: 9px 10px 0 0;">px</span> <span class="ed_item ed_color_pick ed_bordercolor_pick" style="margin-top: 3px;" onclick="mw.wysiwyg.request_border_color(this);"><span></span></span> </div>
      </div>
    </li>
    <li class="ts_main_li mw-designtype-element"> <a class="ts_main_a dd_design_bg" href="javascript:;"><?php _e("Background"); ?></a>
      <div class="ts_action ts_bg_action" style="width: 200px;">
        <div class="ts_action_item">
          <div class="ts_action_centerer">
          <span class="mw-close ed_none_bgcolor" style="position: absolute;right: 12px;top: 12px;" onclick="mw.current_element.style.backgroundColor='transparent'"></span>
          <span class="ed_label left" onclick="mw.wysiwyg.request_change_bg_color(mwd.getElementById('ts_element_bgcolor'));"><?php _e("Background Color"); ?>&nbsp;</span>
          <span class="ed_item ed_color_pick right" id="ts_element_bgcolor" onclick="mw.wysiwyg.request_change_bg_color(this);"><span></span></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer">
            <span class="mw-close ed_none_bgimage" style="position: absolute;right: 12px;top: 12px;" onclick="mw.current_element.style.backgroundImage='none'"></span>
            <a href="javascript:;" class="ed_label left" onclick="mw.wysiwyg.request_bg_image();"><?php _e("Background Image"); ?></a>
            <span class="ed_item right" id="ed_bg_image_status" onclick="mw.wysiwyg.request_bg_image();"></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <span class="ed_label left"><?php _e("Repeat"); ?></span>
            <div class="mw-dropdown mw-dropdown-type-wysiwyg hovered" style="margin-left: -5px;margin-top: -3px;width: auto;" id="ts_bg_repeat" title="Background Repeat" data-value="none"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val" style="width: auto"><?php _e("No-Repeat"); ?></span> </span>
              <div class="mw-dropdown-content">
                <ul style="width: 100px;">
                  <li value="no-repeat"><a href="javascript:;"><?php _e("No-Repeat"); ?></a></li>
                  <li value="repeat-x"><a href="javascript:;"><?php _e("Horizontally"); ?></a></li>
                  <li value="repeat-y"><a href="javascript:;"><?php _e("Vertically"); ?></a></li>
                  <li value="repeat"><a href="javascript:;"><?php _e("Both"); ?></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer">
            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider left hovered" style="width: auto;margin-left: -6px;" id="ts_bg_position"> <span class="mw-dropdown-value"> <span class="dd_rte_arr"></span> <span class="mw-dropdown_val" style="width: auto;"><?php _e("Background Position"); ?></span> </span>
              <div class="mw-dropdown-content">
                <ul style="width: 100%">
                  <li value="true">
                    <div class="square_map">
                      <table cellpadding="2" cellspacing="0" align="center">
                        <tr>
                          <td><span class="square_map_item square_map_item_default active" data-value="left top"><?php _e("Left Top"); ?></span></td>
                          <td><span class="square_map_item" data-value="center top"><?php _e("Center Top"); ?></span></td>
                          <td><span class="square_map_item" data-value="right top"><?php _e("Right Top"); ?></span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" data-value="left center"><?php _e("Left Center"); ?></span></td>
                          <td><span class="square_map_item" data-value="center center"><?php _e("Center Center"); ?></span></td>
                          <td><span class="square_map_item" data-value="right center"><?php _e("Right Center"); ?></span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" data-value="left bottom"><?php _e("Left Bottom"); ?></span></td>
                          <td><span class="square_map_item" data-value="center bottom"><?php _e("Center Bottom"); ?></span></td>
                          <td><span class="square_map_item" data-value="right bottom"><?php _e("Right Bottom"); ?></span></td>
                        </tr>
                      </table>
                      <span class="square_map_value">&nbsp;</span> </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </li>
    <li class="ts_main_li"> <a class="ts_main_a dd_design_fx" href="javascript:;"><?php _e("Effects"); ?></a>
      <div class="ts_action ts_fx_action" style="width: 200px;">
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Drop Shadow"); ?></span>
          <div class="ts_action" style="width: 260px;">
            <div class="ts_action_item"> <span class="ed_label left" style="margin-top: 14px;"><?php _e("Position"); ?></span>
              <div id="ed_shadow" class="fx_canvas_slider left" style="width: 40px;height: 40px;"></div>
              <span class="mw_dlm" style="height: 40px;margin-top: 0"></span> <span class="ed_label left" style="margin-top: 14px;"><?php _e("Blur"); ?></span>
              <div id="ed_shadow_strength" class="fx_canvas_slider left" style="width: 30px;height: 9px;background-image: none;margin-top: 17px"></div>
              <span class="mw_dlm" style="height: 40px;margin-top: 0"></span> <span class="ed_item ed_color_pick ed_shadow_color left" style="margin-top: 9px;" data-color="696969" onclick="mw.wysiwyg.request_change_shadow_color(this);"><span></span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"> <span class="ed_label"><?php _e("Opacity"); ?></span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider opacity-slider es_item left" id="opacity_slider" data-value="100" data-type="opacity"></div>
              <span class="slider_val">
              <input type="text" value="" name="opacity_slider">
              <span class="slider_val_label">%</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Radius"); ?></span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider radius-slider es_item left" id="radius_slider" data-type="border-radius"></div>
              <span class="slider_val">
              <input type="text" value="" name="radius_slider">
              <span class="slider_val_label">px</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Rotation"); ?></span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider rotate-slider es_item left"  data-min="-3.14" data-max="3.14" data-step="0.001" data-custom="mw.css3fx.rotate" id="rotate_slider"></div>
              <span class="slider_val">
              <input type="text" value="" name="rotate_slider">
              <span class="slider_val_label">&deg;</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Pespective"); ?></span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider perspective-slider left" data-min="-55" data-max="55" data-value="0" data-custom="mw.css3fx.perspective" id="perspective_slider"></div>
              <span class="slider_val">
              <input type="text" value="" name="perspective_slider">
              <span class="slider_val_label">%</span></span> </div>
          </div>
        </div>
      </div>
    </li>
    <li class="ts_main_li mw-designtype-image"> <a class="ts_main_a dd_design_img" href="javascript:;"><?php _e("Image"); ?></a>
      <div class="ts_action ts_image_action" style="width: 200px;" contenteditable="false">
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <?php /*<span class="ed_item ed_nobg ed_item_image_text left" onclick="mw.image.enterText();"> <span></span> </span>*/ ?> <span class="ed_item ed_nobg ed_item_image_gray left"  onclick="mw.image.vintage();" title="Vintage Effect"> <span></span> </span><span class="ed_item ed_nobg ed_item_image_gray left"  onclick="mw.image.grayscale();" title="Convert to Grayscale"> <span></span> </span><span class="mw_dlm left" style="margin: 2px 6px"></span> <span class="ed_item ed_nobg ed_item_image_rotate left"  onclick="mw.image.rotate();"> <span></span> </span> <span class="mw_dlm left" style="margin: 2px 6px"></span> <?php /*<span class="ed_item ed_nobg ed_item_image_link left" onclick="mw.image.linkIt();"> <span></span> </span>*/ ?> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <a href="javascript:;" class="ed_btn" onclick="mw.image.description.init('#ts_image_description');"><?php _e("Add Description"); ?></a>
            <textarea class="desc_area" id="ts_image_description"></textarea>
          </div>
        </div>
      </div>
    </li>
  </ul>

</div>
