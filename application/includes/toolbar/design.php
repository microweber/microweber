
<div id="design_bnav" class="mw-defaults toolbar_bnav" style="width: 160px;">
  <div id="design_bnav_handle"></div>
  <ul class="ts_main_ul">
    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_size" href="javascript:;">Size</a>
      <div class="ts_action" style="width: 190px;top: 0;">
        <div class="ts_action_item"> <span class="ed_label left">Width</span>
          <div class="ed_slider width-slider es_item left" id="width_slider" data-onstart="width_slider_onstart" data-max="999" data-min="100" data-type="width"></div>
          <span class="slider_val">
          <input type="text" name="width_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
        <div class="ts_action_item"> <span class="ed_label left">Auto</span>
          <input type="checkbox" class="mwcheck" id="ed_auto_width" />
        </div>
                <div class="ts_action_item"> <span class="ed_label left">Height</span>
          <div class="ed_slider height-slider es_item left" id="height_slider" data-onstart="" data-max="999" data-min="10" data-type="height"></div>
          <span class="slider_val">
          <input type="text" name="height_slider" value="" />
          <span class="slider_val_label">px</span> </span> </div>
        <div class="ts_action_item"> <span class="ed_label left">Auto</span>
          <input type="checkbox" class="mwcheck" id="ed_auto_height" />
        </div>
      </div>

    </li>
    <li class="ts_main_li"> <a class="ts_main_a dd_design_spacing" href="javascript:;">Spacing</a>
      <div class="ts_action" style="width: 200px;">
        <div class="ts_action_item mw-designtype-universal">
          <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="margin_selector" data-for="margin_slider"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val">Margin</span> </span>
            <div class="mw_dropdown_fields">
              <ul style="width: 100%">
                <li value="true">
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="margin-top">Top</span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" data-value="margin-left">Left</span></td>
                        <td><span class="square_map_item square_map_item_default active" data-value="margin">All</span></td>
                        <td><span class="square_map_item" data-value="margin-right">Right</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="margin-bottom">Bottom</span></td>
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
          <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="padding_selector" data-for="padding_slider"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val">Padding</span> </span>
            <div class="mw_dropdown_fields">
              <ul style="width: 100%">
                <li value="true">
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="padding-top">Top</span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" data-value="padding-left">Left</span></td>
                        <td><span class="square_map_item square_map_item_default active" data-value="padding">All</span></td>
                        <td><span class="square_map_item" data-value="padding-right">Right</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" data-value="padding-bottom">Bottom</span></td>
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
    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_border" href="javascript:;">Border</a>
      <div class="ts_action" style="width: 185px;">
        <div class="ts_action_item ts_border_position_selector"> <a class="border-style none" data-val="none" title="<?php _e("Remove Borders"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style all active" data-val="border" title="<?php _e("All"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bleft" data-val="borderLeft" title="<?php _e("Border Left"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bright" data-val="borderRight" title="<?php _e("Border Right"); ?>"></a><span class="mw_dlm left"></span> <a class="border-style top" data-val="borderTop" title="<?php _e("Border Top"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bottom" data-val="borderBottom" title="<?php _e("Border Bottom"); ?>"></a> </div>
        <div class="ts_action_item">
          <div class="mw_dropdown mw_dropdown_type_wysiwyg dd_border_selector" style="margin: 0 8px 0 0;width: auto;"  title="Border Style" data-value="solid"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val" style="width: 62px;"><span class="border_selector" style="border-bottom-style: solid">Solid</span></span> </span>
            <div class="mw_dropdown_fields">
              <ul style="width: auto">
                <li value="solid"><a href="javascript:;"><span class="border_selector" style="border-bottom-style: solid">Solid</span></a></li>
                <li value="dotted"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dotted">Dotted</span></a></li>
                <li value="dashed"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dashed">Dashed</span></a></li>
              </ul>
            </div>
          </div>
          <div class="mw_dropdown mw_dropdown_type_wysiwyg dd_borderwidth_Selector" style="margin-left: -5px;width: auto"  title="Border Width" data-value="0"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val" style="width: auto">0</span> </span>
            <div class="mw_dropdown_fields">
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
    <li class="ts_main_li mw-designtype-element"> <a class="ts_main_a dd_design_bg" href="javascript:;">Background</a>
      <div class="ts_action ts_bg_action" style="width: 200px;">
        <div class="ts_action_item">
          <div class="ts_action_centerer">
          <span class="ed_label left" onclick="mw.wysiwyg.request_change_bg_color(mwd.getElementById('ts_element_bgcolor'));">Background color</span>
          <span class="ed_item ed_color_pick left" id="ts_element_bgcolor" onclick="mw.wysiwyg.request_change_bg_color(this);"><span></span></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer">
            <span class="mw-close ed_none_bgimage" style="position: absolute;right: 12px;top: 12px;" onclick="mw.current_element.style.backgroundImage='none'"></span>
            <a href="javascript:;" class="ed_label left" onclick="mw.wysiwyg.request_bg_image();">Background Image</a>
            <span class="ed_item" id="ed_bg_image_status" onclick="mw.wysiwyg.request_bg_image();"></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <span class="ed_label left">Repeat</span>
            <div class="mw_dropdown mw_dropdown_type_wysiwyg hovered" style="margin-left: -5px;margin-top: -3px;width: auto;" id="ts_bg_repeat" title="Background Repeat" data-value="none"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val" style="width: auto">No-Repeat</span> </span>
              <div class="mw_dropdown_fields">
                <ul style="width: 100px;">
                  <li value="no-repeat"><a href="javascript:;">No-Repeat</a></li>
                  <li value="repeat-x"><a href="javascript:;">Horizontally</a></li>
                  <li value="repeat-y"><a href="javascript:;">Vertically</a></li>
                  <li value="repeat"><a href="javascript:;">Both</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer">
            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left hovered" style="width: auto;margin-left: -6px;" id="ts_bg_position"> <span class="mw_dropdown_val_holder"> <span class="dd_rte_arr"></span> <span class="mw_dropdown_val" style="width: auto;">Background Position</span> </span>
              <div class="mw_dropdown_fields">
                <ul style="width: 100%">
                  <li value="true">
                    <div class="square_map">
                      <table cellpadding="2" cellspacing="0" align="center">
                        <tr>
                          <td><span class="square_map_item square_map_item_default active" data-value="left top">Left Top</span></td>
                          <td><span class="square_map_item" data-value="center top">Center Top</span></td>
                          <td><span class="square_map_item" data-value="right top">Right Top</span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" data-value="left center">Left Center</span></td>
                          <td><span class="square_map_item" data-value="center center">Center Center</span></td>
                          <td><span class="square_map_item" data-value="right center">Right Center</span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" data-value="left bottom">Left Bottom</span></td>
                          <td><span class="square_map_item" data-value="center bottom">Center Bottom</span></td>
                          <td><span class="square_map_item" data-value="right bottom">Right Bottom</span></td>
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
    <li class="ts_main_li"> <a class="ts_main_a dd_design_fx" href="javascript:;">Effects</a>
      <div class="ts_action ts_fx_action" style="width: 200px;">
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label">Drop Shadow</span>
          <div class="ts_action" style="width: 260px;">
            <div class="ts_action_item"> <span class="ed_label left" style="margin-top: 14px;">Position</span>
              <div id="ed_shadow" class="fx_canvas_slider left" style="width: 40px;height: 40px;"></div>
              <span class="mw_dlm" style="height: 40px;margin-top: 0"></span> <span class="ed_label left" style="margin-top: 14px;">Blur</span>
              <div id="ed_shadow_strength" class="fx_canvas_slider left" style="width: 30px;height: 9px;background-image: none;margin-top: 17px"></div>
              <span class="mw_dlm" style="height: 40px;margin-top: 0"></span> <span class="ed_item ed_color_pick ed_shadow_color left" style="margin-top: 9px;" data-color="696969" onclick="mw.wysiwyg.request_change_shadow_color(this);"><span></span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"> <span class="ed_label">Opacity</span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider opacity-slider es_item left" id="opacity_slider" data-value="100" data-type="opacity"></div>
              <span class="slider_val">
              <input type="text" value="" name="opacity_slider">
              <span class="slider_val_label">%</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label">Radius</span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider radius-slider es_item left" id="radius_slider" data-type="border-radius"></div>
              <span class="slider_val">
              <input type="text" value="" name="radius_slider">
              <span class="slider_val_label">px</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label">Rotation</span>
          <div class="ts_action" style="width: 120px;">
            <div class="ts_action_item">
              <div class="ed_slider rotate-slider es_item left"  data-min="-3.14" data-max="3.14" data-step="0.001" data-custom="mw.css3fx.rotate" id="rotate_slider"></div>
              <span class="slider_val">
              <input type="text" value="" name="rotate_slider">
              <span class="slider_val_label">&deg;</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label">Pespective</span>
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
    <li class="ts_main_li mw-designtype-image"> <a class="ts_main_a dd_design_img" href="javascript:;">Image</a>
      <div class="ts_action ts_image_action" style="width: 200px;">
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <span class="ed_item ed_nobg ed_item_image_text left" onclick="mw.image.enterText();"> <span></span> </span> <span class="mw_dlm left" style="margin: 2px 6px"></span> <span class="ed_item ed_nobg ed_item_image_rotate left"  onclick="mw.image.rotate();"> <span></span> </span> <span class="mw_dlm left" style="margin: 2px 6px"></span> <span class="ed_item ed_nobg ed_item_image_link left" onclick="mw.image.linkIt();"> <span></span> </span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <a href="javascript:;" class="ed_btn" onclick="mw.image.description.init('#ts_image_description');">Add Description</a>
            <textarea class="desc_area" id="ts_image_description"></textarea>
          </div>
        </div>
      </div>
    </li>
  </ul>
  <?php /* <div id="mw_design_disabler" class="mw_disabler"></div> */ ?>
</div>
