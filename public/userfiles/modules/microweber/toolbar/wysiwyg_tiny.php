<div id="mw_small_editor" class="mw-defaults mw_editor mw_small_editor">
        <div class="mw_small_editor_top">

            <span title="Insert Media" data-command="custom-media" class="mw_editor_btn mw_editor_image"><span class="ed-ico"></span></span>
            <span class="mw_dlm"></span>
            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit/Remove Link"); ?>"><span class="ed-ico"></span></span>

            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family" id="font_family_selector_small" title="<?php _e("Font"); ?>" data-value="Arial">
              <span class="mw-dropdown-value">

                  <span class="mw-dropdown-val">Arial</span>
              </span>
              <div class="mw-dropdown-content">
                <ul>
                  <li value="Arial"><a href="javascript:;" style="font-family:Arial">Arial</a></li>
                  <li value="Tahoma"><a href="javascript:;" style="font-family:Tahoma">Tahoma</a></li>
                  <li value="Verdana"><a href="javascript:;" style="font-family:Verdana">Verdana</a></li>
                  <li value="Georgia"><a href="javascript:;" style="font-family:Georgia">Georgia</a></li>
                  <li value="Times New Roman"><a href="javascript:;" style="font-family: 'Times New Roman'">Times New Roman</a></li>
                </ul>
              </div>
            </div>
            <span class="mw_dlm"></span>
            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_size" id="font_size_selector_small" title="<?php _e("Font Size"); ?>">
                <span class="mw-dropdown-value">

                            <span class="mw-dropdown-val" ><?php _e('Size'); ?></span>
                        </span>
              <div class="mw-dropdown-content">
                        <ul>
                          <li value="10"><a href="javascript:;">10</a></li>
                          <li value="11"><a href="javascript:;">11</a></li>
                          <li value="12"><a href="javascript:;">12</a></li>
                          <li value="14"><a href="javascript:;">14</a></li>
                          <li value="16"><a href="javascript:;">16</a></li>
                          <li value="18"><a href="javascript:;">18</a></li>
                          <li value="20"><a href="javascript:;">20</a></li>
                          <li value="22"><a href="javascript:;">22</a></li>
                          <li value="24"><a href="javascript:;">24</a></li>
                          <li value="36"><a href="javascript:;">36</a></li>
                          <li value="72"><a href="javascript:;">72</a></li>
                          <li onclick="mw.wysiwyg.fontSizePrompt()"><a href="javascript:;">...</a></li>
                    </ul>
                      </div>
            </div>


            <?php /*<span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span>*/ ?>
            <div class="mw_clear">&nbsp;</div>
        </div>
        <div class="mw_small_editor_bottom">
          <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
          <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
          <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>
          <span class="mw_dlm"></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>
            <span title="Remove Formatting" data-command="removeformat" class="mw_editor_btn mw_editor_remove_formatting"><span class="ed-ico"></span></span>
            <div class="mw_clear">&nbsp;</div>
        </div>

    </div>
