

<div class="editor_wrapper editor_wrapper_tabled" id="liveedit_wysiwyg">
  <div class="wysiwyg-table">



      <div class="wysiwyg-cell">


          <div class="relative">

          <span class="mw_editor_btn mw_editor_t wysiwyg-convertible-toggler wysiwyg-convertible-toggler-1000">
               <span class="ed-ico">text_format</span>
            </span>
          <div class="wysiwyg-convertible wysiwyg-convertible-1000">



          <span class="mw_editor_btn mw_editor_t wysiwyg-convertible-toggler wysiwyg-convertible-toggler">
               <span class="ed-ico">text_format</span>
            </span>


      <module type="editor/fonts" id="font_family_selector_main" />
      <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_size" id="font_size_selector_main" title="<?php _e("Font Size"); ?>">
                  <span class="mw-dropdown-value">
                 <span class="mw-dropdown-val" ><?php _e('Size'); ?></span> </span>
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
                  <li value="48"><a href="javascript:;">48</a></li>
                  <li value="72"><a href="javascript:;">72</a></li>
                  <li onclick="mw.wysiwyg.fontSizePrompt()">
                      <a href="javascript:;" style=" z-index: 1; background-color: #f5f5f5; text-align: center; padding-top: 0px; padding-bottom: 3px;">
                          <small style="text-transform: lowercase; color: black;"><?php _e('more'); ?></small>
                      </a>
                  </li>
              </ul>
          </div>
      </div>
      <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_insert" id="wysiwyg_insert" title="<?php _e("Insert"); ?>">
                  <span class="mw-dropdown-value">
                      <span class="mw-dropdown-val">
                <?php _e("Insert"); ?>
                </span> </span>
          <div class="mw-dropdown-content">
              <ul>
                  <li value="table"><a href="javascript:;" style="font-size: 10px">
                          <?php _e("Table"); ?>
                      </a>
                  </li>
                  <li value="hr"><a href="javascript:;" style="font-size: 10px">
                          <?php _e("Horizontal Rule"); ?>
                      </a>
                  </li>
                  <li value="pre"><a href="javascript:;" style="font-size: 10px">
                          <?php _e("Pre formatted"); ?>
                      </a>
                  </li>
                  <li value="code"><a href="javascript:;" style="font-size: 10px">
                          <?php _e("Code format"); ?>
                      </a>
                  </li>
                  <li value="quote"><a href="#" style="font-size: 10px"><?php _e("Quote"); ?></a></li>
                  <li value="icon"><a href="#" style="font-size: 10px"><?php _e("Icon"); ?></a></li>
                  <li value="insert_html"><a href="javascript:;" style="font-size: 10px">
                          <?php _e("HTML"); ?>
                      </a>
                  </li>




                  <?php /*<li value="quote"><a href="#" style="font-size: 10px"><?php _e("Quote"); ?></a></li>*/ ?>
              </ul>
          </div>
      </div>
      </div>
      </div>
      </div>




    <div class="wysiwyg-cell"> <span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span> </div>
    <div class="wysiwyg-cell">
      <div class="relative">
            <span class="mw_editor_btn mw_editor_t wysiwyg-convertible-toggler wysiwyg-convertible-toggler-1000">
               <span class="ed-ico">format_bold</span>
            </span>
            <div class="wysiwyg-convertible wysiwyg-convertible-1000">
                <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_strike" data-command="strikethrough" title="<?php _e("Strike Through"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="<?php _e("Remove Formatting"); ?>"><span class="ed-ico"></span></span>

            </div>
            </div>
            </div>
                <div class="wysiwyg-cell">
                    <div class="relative">
                        <span class="mw_editor_btn mw_editor_t wysiwyg-convertible-toggler wysiwyg-convertible-toggler-1000">
                           <span class="ed-ico">format_align_left</span>
                        </span>
                    <div class="wysiwyg-convertible wysiwyg-convertible-1000">
                        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="<?php _e("Ordered List"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="<?php _e("Unordered List"); ?>"><span class="ed-ico"></span></span>
                    </div>
                </div>
    </div>



    <div class="wysiwyg-cell">
        <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_format" id="format_main" title="Format" data-value="">
                  <span class="mw-dropdown-value">
                      <span class="mw-dropdown-val">
                        <?php _e("Format"); ?>
                      </span>
                  </span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="h1"><a href="#">
                            <h1>
                                <?php _e("Heading"); ?>
                                1</h1>
                        </a></li>
                    <li value="h2"><a href="#">
                            <h2>
                                <?php _e("Heading"); ?>
                                2</h2>
                        </a></li>
                    <li value="h3"><a href="#">
                            <h3>
                                <?php _e("Heading"); ?>
                                3</h3>
                        </a></li>
                    <li value="h4"><a href="#">
                            <h4>
                                <?php _e("Heading"); ?>
                                4</h4>
                        </a></li>
                    <li value="h5"><a href="#">
                            <h5>
                                <?php _e("Heading"); ?>
                                5</h5>
                        </a></li>
                    <li value="h6"><a href="#">
                            <h6>
                                <?php _e("Heading"); ?>
                                6</h6>
                        </a></li>
                    <li value="p"><a href="#">
                            <p>
                                <?php _e("Paragraph"); ?>
                            </p>
                        </a></li>
                    <li value="div"><a href="#">
                            <div>
                                <?php _e("Block"); ?>
                            </div>
                        </a></li>


                    <li value="pre"><a href="#">
                            <div>
                                <?php _e("Pre formatted"); ?>
                            </div>
                        </a>
                    </li>




                </ul>
            </div>
        </div>
    </div>
    <div class="wysiwyg-cell">
      <div class="relative">
          <span class="mw_editor_btn mw_editor_font_color wysiwyg-convertible-toggler wysiwyg-convertible-toggler-1024"> <span class="ed-ico"></span> </span>
        <div class="wysiwyg-convertible wysiwyg-convertible-1024">

            <span class="mw_editor_btn mw_editor_font_color" id="mw_editor_font_color" title="<?php _e("Font Color"); ?>">
                <span class="ed-ico"></span>
                <input type="color"
                       class="mw-color-picker-live-edit-input"
                       oninput="mw.wysiwyg.fontColor(this.value)"
                       onmousedown="event.stopPropagation()"
                       onclick="event.stopPropagation()"
                       onmouseup="event.stopPropagation()"
                       >
            </span>
            <span class="mw_editor_btn mw_editor_font_background_color" title="<?php _e("Font Background Color"); ?>">
                <span class="ed-ico"></span>
                <input type="color"
                       class="mw-color-picker-live-edit-input"
                       oninput="mw.wysiwyg.fontbg(this.value)"
                       onmousedown="event.stopPropagation()"
                       onclick="event.stopPropagation()"
                       onmouseup="event.stopPropagation()"
                       >
            </span>
        </div>
      </div>
    </div>
    <div class="wysiwyg-cell">
      <div class="relative"> <span class="mw_editor_btn mw_editor_ul wysiwyg-convertible-toggler wysiwyg-convertible-toggler-1440">  <span class="ed-ico"></span> </span>
        <div class="wysiwyg-convertible wysiwyg-convertible-1440">
            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_unlink" data-command="custom-unlink" title="<?php _e("Remove Link"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_indent" data-command="indent" title="<?php _e("Indent"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_outdent" data-command="outdent" title="<?php _e("Outdent"); ?>"><span class="ed-ico"></span></span>



        </div>
      </div>
    </div>

    <div class="wysiwyg-cell visible-1440"> <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span> </div>
      <div class="wysiwyg-cell">
        <span title="Paste from word" onclick="mw.wysiwyg.pasteFromWordUI();" class="mw_editor_btn mw_editor_paste_from_word">
            <span class="ed-ico"></span>
        </span>
      </div>
      <div class="wysiwyg-cell">



          <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_format" id="format_main" title="Format" data-value="">
                  <span class="mw-dropdown-value">
                      <span class="mw-dropdown-val">
                        <?php _e("Columns"); ?>
                      </span>
                  </span>
              <div class="mw-dropdown-content">
                  <ul>
                      <li><a>One column</a></li>
                      <li><a>2 columns</a></li>
                      <li><a>3 columns</a></li>
                      <li><a>4 columns</a></li>
                      <li><a>5 columns</a></li>
                      <li><a>
                        <span class="mw_editor_btn mw-icon-bin mw-handle-menu-item-icon"></span>
                          Remove</a>
                      </li>
                  </ul>
              </div>
          </div>




      </div>
      <div class="wysiwyg-cell">
          <span class="mw_editor_btn mw-cloneable-control-item mw-cloneable-control-prev" title="Move backward" ></span>
          <span class="mw_editor_btn mw-cloneable-control-item mw-cloneable-control-plus" title="Clone"></span>
          <span class="mw_editor_btn mw-cloneable-control-item mw-cloneable-control-minus" title="Remove"></span>
          <span class="mw_editor_btn mw-cloneable-control-item mw-cloneable-control-next" title="Move forward"></span>
      </div>



    <?php if(file_exists(TEMPLATE_DIR.'template_settings.php')){ ?>

<?php
        /*    <div class="wysiwyg-cell"><span class="mw_editor_btn editor-template-settings" id="toolbar-template-settings" title="<?php _e("Template Settings"); ?>"><span class="ed-ico"></span></span></div>
*/

        ?>

    <?php } ?>
    <?php event_trigger('live_edit_toolbar_btn'); ?>
  </div>
</div>
