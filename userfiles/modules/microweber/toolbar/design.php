
<div id="design_bnav" class="mw-defaults toolbar_bnav" style="width: 220px;">



<div id="design_bnav_handle"><a id="design_close" class="mw_ex_tools" href="#design_bnav" onclick="mw.$('.mw_ex_tools').removeClass('active');"></a></div>

  <ul class="ts_main_ul">

    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_size" href="javascript:;"><?php _e("Size"); ?></a>
      <div class="ts_action">
        <div class="ts_action_item">
          <span class="ed_label pull-left"><?php _e("Width"); ?></span>
          <input type="number" class="mw-ui-field mw-ui-field-small" id="mw_ts_width" min="0" step="10" />
           </div>
        <div class="ts_action_item"> <span class="ed_label pull-left"><?php _e("Auto"); ?></span>
          <input type="checkbox" class="mwcheck" id="ed_auto_width" />
        </div>
        <div class="ts_action_item"> <span class="ed_label pull-left"><?php _e("Height"); ?></span>
         <input type="number" class="mw-ui-field mw-ui-field-small" id="mw_ts_height" min="0" step="10" />
        </div>
        <div class="ts_action_item"> <span class="ed_label pull-left"><?php _e("Auto"); ?></span>
          <input type="checkbox" class="mwcheck" id="ed_auto_height" />
        </div>
      </div>

    </li>
    <li class="ts_main_li"> <a class="ts_main_a dd_design_spacing" href="javascript:;"><?php _e("Spacing"); ?></a>
      <div class="ts_action">
        <div class="ts_action_item mw-designtype-universal">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider left" id="margin_selector" data-for="mw_ts_margin"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val"><?php _e("Margin"); ?></span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: 100%">
                <li>
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" value="margin-top"><?php _e("Top"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" value="margin-left"><?php _e("Left"); ?></span></td>
                        <td><span class="square_map_item square_map_item_default active" value="margin"><?php _e("All"); ?></span></td>
                        <td><span class="square_map_item" value="margin-right"><?php _e("Right"); ?></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" value="margin-bottom"><?php _e("Bottom"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <span class="square_map_value">&nbsp;</span> </div>
                </li>
              </ul>
            </div>
          </div>
          <input type="number" class="mw-ui-field mw-ui-field-small" id="mw_ts_margin" data-type="margin" />


           </div>
        <div class="ts_action_item mw-designtype-element">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider pull-left" id="padding_selector" data-for="mw_ts_padding"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val"><?php _e("Padding"); ?></span> </span>
            <div class="mw-dropdown-content">
              <ul style="width: 100%">
                <li>
                  <div class="square_map">
                    <table cellpadding="2" cellspacing="0" align="center">
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" value="padding-top"><?php _e("Top"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="square_map_item" value="padding-left"><?php _e("Left"); ?></span></td>
                        <td><span class="square_map_item square_map_item_default active" value="padding"><?php _e("All"); ?></span></td>
                        <td><span class="square_map_item" value="padding-right"><?php _e("Right"); ?></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="square_map_item" value="padding-bottom"><?php _e("Bottom"); ?></span></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <span class="square_map_value">&nbsp;</span> </div>
                </li>
              </ul>
            </div>
          </div>
                    <input type="number" class="mw-ui-field mw-ui-field-small" id="mw_ts_padding" data-type="padding" />

           </div>
      </div>
    </li>
    <li class="ts_main_li mw-designtype-universal"> <a class="ts_main_a dd_design_border" href="javascript:;"><?php _e("Border"); ?></a>
      <div class="ts_action">
        <div class="ts_action_item ts_border_position_selector"> <a class="border-style none" data-val="none" title="<?php _e("Remove Borders"); ?>"></a> <span class="mw_dlm pull-left"></span> <a class="border-style all active" data-val="border" title="<?php _e("All"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bleft" data-val="borderLeft" title="<?php _e("Border Left"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bright" data-val="borderRight" title="<?php _e("Border Right"); ?>"></a><span class="mw_dlm left"></span> <a class="border-style top" data-val="borderTop" title="<?php _e("Border Top"); ?>"></a> <span class="mw_dlm left"></span> <a class="border-style bottom" data-val="borderBottom" title="<?php _e("Border Bottom"); ?>"></a> </div>
        <div class="ts_action_item">
          <div class="mw-dropdown mw-dropdown-type-wysiwyg dd_border_selector" style="margin: 0 8px 0 0;width: auto;"  title="<?php _e("Border Style"); ?>" data-value="solid">
            <span class="mw-dropdown-value">
                <span class="mw-dropdown-arrow"></span>
                <span class="mw-dropdown-val" style="width: 62px;padding: 12px 7px;">
                    <span class="border_selector" style="border-bottom-style: solid;">Solid</span>
                </span> </span>
            <div class="mw-dropdown-content">
              <ul >
                <li value="solid"><a href="javascript:;"><span class="border_selector" style="border-bottom-style: solid"><?php _e("Solid"); ?></span></a></li>
                <li value="dotted"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dotted"><?php _e("Dotted"); ?></span></a></li>
                <li value="dashed"><a href="javascript:;"><span class="border_selector" style="border-bottom-style:dashed"><?php _e("Dashed"); ?></span></a></li>
              </ul>
            </div>
          </div>
          <div class="mw-dropdown mw-dropdown-type-wysiwyg dd_borderwidth_Selector" style="margin-left: -5px;width: auto"  title="<?php _e("Border Width"); ?>" data-value="0"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val" >0</span> </span>
            <div class="mw-dropdown-content">
              <ul >
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
          <span class="slider_val_label pull-left" style="margin: 9px 10px 0 0;">px</span> <span class="ed_item ed_color_pick ed_bordercolor_pick" style="margin-top: 3px;" onclick="mw.wysiwyg.request_border_color(this);"><span></span></span> </div>
      </div>
    </li>
    <li class="ts_main_li mw-designtype-element"> <a class="ts_main_a dd_design_bg" href="javascript:;"><?php _e("Background"); ?></a>
      <div class="ts_action ts_bg_action">
        <div class="ts_action_item">
          <div class="ts_action_centerer">
          <span class="mw-icon-close ed_none_bgcolor" style="cursor:pointer; position: absolute;right: 12px;top: 12px;" onclick="$('.element-current').css('backgroundColor', 'transparent');"></span>
          <span class="ed_label pull-left" onclick="mw.wysiwyg.request_change_bg_color(mwd.getElementById('ts_element_bgcolor'));"><?php _e("Background Color"); ?>&nbsp;</span>
          <span class="ed_item ed_color_pick right" id="ts_element_bgcolor" onclick="mw.wysiwyg.request_change_bg_color(this);"><span></span></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer">
            <span class="mw-icon-close ed_none_bgimage" style="cursor:pointer; position: absolute;right: 12px;top: 12px;" onclick="$('.element-current').css('backgroundImage', 'none');"></span>
            <a href="javascript:;" class="ed_label pull-left" onclick="mw.wysiwyg.request_bg_image();"><?php _e("Background Image"); ?></a>
            <span class="ed_item right" id="ed_bg_image_status" onclick="mw.wysiwyg.request_bg_image();"></span> </div>
        </div>
        <div class="ts_action_item">
          <div class="ts_action_centerer"> <span class="ed_label pull-left"><?php _e("Repeat"); ?></span>
            <div class="mw-dropdown mw-dropdown-type-wysiwyg hovered" style="margin-left: -5px;margin-top: -3px;width: auto;" id="ts_bg_repeat" title="Background Repeat" data-value="none"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val" ><?php _e("No-Repeat"); ?></span> </span>
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
            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown_func_slider hovered" style="width: 145px;margin-left: -6px;" id="ts_bg_position">
                <span class="mw-dropdown-value">
                    <span class="mw-dropdown-arrow"></span>
                    <span class="mw-dropdown-val" style="width: auto;"><?php _e("Background Position"); ?></span>
                </span>
              <div class="mw-dropdown-content">
                <ul style="width: 100%">
                  <li>
                    <div class="square_map">
                      <table cellpadding="2" cellspacing="0" align="center">
                        <tr>
                          <td><span class="square_map_item square_map_item_default active" value="left top"><?php _e("Left Top"); ?></span></td>
                          <td><span class="square_map_item" value="center top"><?php _e("Center Top"); ?></span></td>
                          <td><span class="square_map_item" value="right top"><?php _e("Right Top"); ?></span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" value="left center"><?php _e("Left Center"); ?></span></td>
                          <td><span class="square_map_item" value="center center"><?php _e("Center Center"); ?></span></td>
                          <td><span class="square_map_item" value="right center"><?php _e("Right Center"); ?></span></td>
                        </tr>
                        <tr>
                          <td><span class="square_map_item" value="left bottom"><?php _e("Left Bottom"); ?></span></td>
                          <td><span class="square_map_item" value="center bottom"><?php _e("Center Bottom"); ?></span></td>
                          <td><span class="square_map_item" value="right bottom"><?php _e("Right Bottom"); ?></span></td>
                        </tr>
                      </table>
                      <span class="square_map_value">&nbsp;</span>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="ts_action_item">
              <div class="ts_action_centerer">
                <span class="ed_label pull-left"><?php _e("Size"); ?></span>
                <div class="mw-dropdown mw-dropdown-type-wysiwyg hovered" style="margin-left: -5px;margin-top: -3px;width: auto;" id="ts_bg_size" title="Background Size" data-value="auto">
                  <span class="mw-dropdown-value">
                    <span class="mw-dropdown-arrow"></span>
                    <span class="mw-dropdown-val"  style="width: 95px;"><?php _e("Auto"); ?></span>
                  </span>
                  <div class="mw-dropdown-content">
                    <ul style="width: 100px;">
                      <li value="contain"><a href="javascript:;"><?php _e("Fit"); ?></a></li>
                      <li value="cover"><a href="javascript:;"><?php _e("Cover"); ?></a></li>
                      <li value="auto"><a href="javascript:;"><?php _e("Auto"); ?></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </li>
    <li class="ts_main_li"> <a class="ts_main_a dd_design_fx" href="javascript:;"><?php _e("Effects"); ?></a>
      <div class="ts_action ts_fx_action">
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Drop Shadow"); ?></span>
          <div class="ts_action"  >
            <div class="ts_action_item ts_action_item_shadow">
              <div class="ts_line">
                <span class="ed_label "><?php _e("Position"); ?></span>
                <div id="ed_shadow" class="fx_canvas_slider" style="width: 40px;height: 40px;"></div>
              </div>
              <div class="ts_line">
                <span class="ed_label "><?php _e("Blur"); ?></span>
                <div id="ed_shadow_strength" class="fx_canvas_slider " style="width: 30px;height: 9px;background-image: none;"></div>
              </div>
              <div class="ts_line">
                <span class="ed_label "><?php _e("Color"); ?></span>
                <span class="ed_item ed_color_pick ed_shadow_color" data-color="696969" onclick="mw.wysiwyg.request_change_shadow_color(this);"><span></span></span>
              </div>
            </div>

          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"> <span class="ed_label"><?php _e("Opacity"); ?></span>
          <div class="ts_action" >
            <div class="ts_action_item">
              <div class="ed_slider opacity-slider es_item pull-left" id="opacity_slider" data-value="100" data-type="opacity"></div>
              <span class="slider_val">
              <input type="text" value="" name="opacity_slider">
              <span class="slider_val_label">%</span></span> </div>
          </div>
        </div>
        <div class="ts_action_item mw-designtype-universal"><span class="ed_label"><?php _e("Radius"); ?></span>
          <div class="ts_action" >
            <div class="ts_action_item">
              <div class="ed_slider radius-slider es_item pull-left" id="radius_slider" data-type="border-radius"></div>
              <span class="slider_val">
              <input type="text" value="" name="radius_slider">
              <span class="slider_val_label">px</span></span> </div>
          </div>
        </div>
      </div>
    </li>

  </ul>

</div>
