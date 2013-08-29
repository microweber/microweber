<div id="mw-text-editor" class="mw-defaults mw_editor">
        <div class="editor_wrapper">

        <div class="low-res-hider">

            <span class="mw_editor_btn mw_editor_undo" data-command="custom-historyUndo" title="<?php _e("Undo"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_redo disabled" data-command="custom-historyRedo" title="<?php _e("Redo"); ?>"><span class="ed-ico"></span></span>


            <span class="mw_dlm"></span>


        </div>

            <span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_dlm"></span>

            <div class="wysiwyg-component">
              <div class="wysiwyg-component-title">
                Font Style
              </div>
              <div class="wysiwyg-component-items" style="width: 190px;">
                <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_strike" data-command="strikethrough" title="<?php _e("Strike Through"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_font_color" data-command="custom-fontcolorpicker" title="<?php _e("Font Color"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_font_background_color" data-command="custom-fontbgcolorpicker" title="<?php _e("Font Background Color"); ?>"><span class="ed-ico"></span></span>
              </div>
            </div>

            <span class="mw_dlm"></span>

            <div class="wysiwyg-component">
              <div class="wysiwyg-component-title">
                Format
              </div>
              <div class="wysiwyg-component-items" style="width: 355px;">

            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_format" id="format_main" title="Format" data-value="">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val"><?php _e("Format"); ?></span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="h1"><a href="#"><h1><?php _e("Heading"); ?> 1</h1></a></li>
                  <li value="h2"><a href="#"><h2><?php _e("Heading"); ?> 2</h2></a></li>
                  <li value="h3"><a href="#"><h3><?php _e("Heading"); ?> 3</h3></a></li>
                  <li value="h4"><a href="#"><h4><?php _e("Heading"); ?> 4</h4></a></li>
                  <li value="h5"><a href="#"><h5><?php _e("Heading"); ?> 5</h5></a></li>
                  <li value="h6"><a href="#"><h6><?php _e("Heading"); ?> 6</h6></a></li>
                  <li value="p"><a href="#"><p><?php _e("Paragraph"); ?></p></a></li>
                  <li value="div"><a href="#"><div><?php _e("Block"); ?></div></a></li>
                  <li value="pre"><a href="#"><div><?php _e("Pre formatted"); ?></div></a></li>
                </ul>
              </div>
            </div>
            <?php /*<div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_fontfx" id="textfx" title="Font Effects" data-value="">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Font FX</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="mw-textfx-3d"><a href="#">3D</a></li>
                  <li value="mw-textfx-neon"><a href="#">Neon</a></li>
                </ul>
              </div>
            </div>*/ ?>



            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_family" id="font_family_selector_main" title="<?php _e("Font"); ?>" data-value="Arial">
              <span class="mw_dropdown_val_holder">
                  <span class="dd_rte_arr"></span>
                  <span class="mw_dropdown_val">Arial</span>
              </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="Arial"><a href="#" style="font-family:Arial">Arial</a></li>
                  <li value="Tahoma"><a href="#" style="font-family:Tahoma">Tahoma</a></li>
                  <li value="Verdana"><a href="#" style="font-family:Verdana">Verdana</a></li>
                  <li value="Georgia"><a href="#" style="font-family:Georgia">Georgia</a></li>
                  <li value="Times New Roman"><a href="#" style="font-family: 'Times New Roman'">Times New Roman</a></li>
                </ul>
              </div>
            </div>




            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_size" id="font_size_selector_main" title="<?php _e("Font Size"); ?>">

                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">10pt</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="1"><a href="#" style="font-size: 10px">8pt</a></li>
                  <li value="2"><a href="#" style="font-size: 13px">10pt</a></li>
                  <li value="3"><a href="#" style="font-size: 16px">12pt</a></li>
                  <li value="4"><a href="#" style="font-size: 18px">14pt</a></li>
                  <li value="5"><a href="#" style="font-size: 24px">18pt</a></li>
                  <li value="6"><a href="#" style="font-size: 32px">24pt</a></li>
                  <li value="7"><a href="#" style="font-size: 48px">36pt</a></li>
                </ul>
              </div>
            </div>


               <span class="mw_dlm"></span>


            <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="<?php _e("Ordered List"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="<?php _e("Unordered List"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_editor_btn mw_editor_indent" data-command="indent" title="<?php _e("Indent"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_outdent" data-command="outdent" title="<?php _e("Outdent"); ?>"><span class="ed-ico"></span></span>




            </div>
            </div>





            <span class="mw_dlm"></span>


            <div class="wysiwyg-component">
              <div class="wysiwyg-component-title">
                Text Align
              </div>
              <div class="wysiwyg-component-items" style="width: 120px;">
                <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
                <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>
             </div>
            </div>

            <span class="mw_dlm"></span>



            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_unlink" data-command="custom-unlink" title="<?php _e("Remove Link"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="<?php _e("Remove Formatting"); ?>"><span class="ed-ico"></span></span>



            <span class="mw_dlm"></span>



            <span class="mw_editor_btn mw_editor_element" title="<?php _e("Create Draggable Element from selected text."); ?>" data-command="custom-createelement"><span class="ed-ico"></span></span>

            <?php /* <span class="mw_editor_btn mw_editor_design mw_ex_tools" title="Show/Hide Design Tools" href="#design_bnav"><span class="ed-ico"></span>Design</span> */ ?>

            <span class="mw_dlm"></span>

             <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_insert"
             id="wysiwyg_insert" title="<?php _e("Insert"); ?>">

                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val"><?php _e("Insert"); ?></span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="hr"><a href="#" style="font-size: 10px"><?php _e("Horizontal Rule"); ?></a></li>
                  <li value="box"><a href="#" style="font-size: 10px"><?php _e("Box"); ?></a></li>
                  <li value="table"><a href="#" style="font-size: 10px"><?php _e("Table"); ?></a></li>
                </ul>
              </div>
            </div>


            <span class="mw_dlm"></span>



            <span class="mw-ui-btn mw-ui-btn-medium mw_ex_tools left" style="border-radius:0;" title="<?php _e("Show/Hide Design Tools"); ?>" href="#design_bnav"><span class="ico ico-extools"></span><?php _e("Design"); ?></span>






             <?php event_trigger('mw_editor_btn'); ?>
            
            
        </div>




    </div>