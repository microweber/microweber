<div id="mw-text-editor" class="mw-defaults mw_editor">
        <div class="toolbar-sections-tabs">
            <ul>
              <li><a href="javascript:;" class="tst-logo" title="Microweber"><span>Microweber</span></a>
                  <div style="display: none">
                      <?php
                          $pt_opts = array();
                          $pt_opts['link'] = "<a href='{link}#tab=pages'>{title}</a>";
                          $pt_opts['list_tag'] = "ul";
                          $pt_opts['ul_class'] = "mw-dropdown-list";
                          $pt_opts['list_item_tag'] = "li";
                          $pt_opts['active_ids'] = CONTENT_ID;
                          $pt_opts['limit'] = 1000;
                          $pt_opts['active_code_tag'] = '   class="active"  ';
                          mw('content')->pages_tree($pt_opts);
                      ?>
                  </div>
              </li>
              <li>
                <a href="javascript:;" class="tst-add" title="Create or manage your content"><span>Create or manage your content</span></a>
                <ul>
                  <li><a href="javascript:;" onclick="mw.quick.post();"><span class="mw-ui-btn-plus left"></span><span class="ico ipost"></span>POST</a></li>
                  <li><a href="javascript:;" onclick="mw.quick.product();"><span class="mw-ui-btn-plus left"></span><span class="ico iproduct"></span>PRODUCT</a></li>
                  <li><a href="javascript:;"onclick="mw.quick.page();"><span class="mw-ui-btn-plus left"></span><span class="ico ipage"></span>PAGE</a></li>
                  <li><a href="javascript:;"><span class="mw-ui-btn-plus left"></span><span class="ico icategory"></span>CATEGORY</a></li>
                  <li><a href="javascript:;"><span>BROWSE MY SITE</span></a></li>
                </ul>
              </li>
              <li><a href="javascript:;" onclick="mw.$('#tab_modules').toggleClass('active');" class="tst-modules" title="Modules & Layouts"><span>Modules & Layouts</span></a></li>
              <li><a href="#design_bnav" class="tst-design mw_ex_tools" title="Design & Settings"><span>Design & Settings</span></a></li>
              <li><a href="javascript:;" class="liveedit_wysiwyg_prev tst-" onclick="mw.liveEditWYSIWYG.slideLeft();"><b style="font-size:20px;position:relative;top:11px;">&lsaquo;</b></a></li>
              <li><a href="javascript:;" class="liveedit_wysiwyg_next tst-" onclick="mw.liveEditWYSIWYG.slideRight();"><b style="font-size:20px;position:relative;top:11px;">&rsaquo;</b></a></li>
            </ul>
         </div>
          <div id="mw-toolbar-right" class="mw-defaults">
              <div class="mw-ui-dropdown right" id="history_dd">
                <a class="mw-ui-btn mw-ui-btn-hover mw-btn-single-ico" onclick="mw.$('#historycontainer').toggle();" title="<?php _e("Drafts"); ?>"><span class="ico ihistory" style="height: 22px;"></span></a>
                <div class="mw-dropdown-content" style="width: 150px;right: -50px;left: auto;display: none;visibility: visible" id="historycontainer">
                  <ul class="mw-dropdown-list">
                      <li>
                          <div id="mw-history-panel"></div>
                      </li>
                  </ul>
                </div>
              </div>
              <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-ui-btn right" onclick="mw.drag.save(this)" id="main-save-btn"><?php _e("Save"); ?></span>
              <div class="mw-ui-dropdown right"> <a href="<?php print mw('url')->current(); ?>/editmode:n" class="mw-ui-btn mw-ui-btn-medium" style="margin-left: 0;"><?php _e("Actions"); ?><span class="ico idownarr right"></span></a>
                <div class="mw-dropdown-content" style="width: 155px;">
                  <ul class="mw-dropdown-list">
                  <li>
                      <a title="Back to Admin" class="mw-ui-btn-blue back_to_admin" href="<?php print $back_url; ?>"><?php _e("Back to Admin"); ?></a>
                      <div class="mw_clear"></div>
                  </li>
                    <li><a href="<?php print mw('url')->current(); ?>?editmode=n"><?php _e("View Website"); ?></a></li>
                    <li><a href="#" onclick="mw.preview();void(0);"><?php _e("Preview"); ?></a></li>
                    <?php if(defined('CONTENT_ID') and CONTENT_ID > 0): ?>
                    <?php $pub_or_inpub  = mw('content')->get_by_id(CONTENT_ID); ?>
                    <li class="mw-set-content-unpublish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><?php _e("Unpublish"); ?></a></li>
                    <li class="mw-set-content-publish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><?php _e("Publish"); ?></a></li>
                    <?php endif; ?>
                    <li><a href="<?php print mw('url')->api_link('logout'); ?>"><?php _e("Logout"); ?></a></li>
                  </ul>
                </div>
              </div>
          </div>


        <div class="editor_wrapper editor_wrapper_tabled" id="liveedit_wysiwyg">
            <div class="wysiwyyg-table">
                <div class="wysiwyyg-cell">
                  <span class="mw_editor_btn mw_editor_undo" data-command="custom-historyUndo" title="<?php _e("Undo"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_redo disabled" data-command="custom-historyRedo" title="<?php _e("Redo"); ?>"><span class="ed-ico"></span></span>
                </div>
                <div class="wysiwyyg-cell">
                    <span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span>
                </div>
                <div class="wysiwyyg-cell">
                    <div class="wysiwyyg-cell-limitter" data-min="">
                        <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_strike" data-command="strikethrough" title="<?php _e("Strike Through"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_font_color" data-command="custom-fontcolorpicker" title="<?php _e("Font Color"); ?>"><span class="ed-ico"></span></span>
                        <span class="mw_editor_btn mw_editor_font_background_color" data-command="custom-fontbgcolorpicker" title="<?php _e("Font Background Color"); ?>"><span class="ed-ico"></span></span>
                    </div>
                </div>
                <div class="wysiwyyg-cell">
                    <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_format" id="format_main" title="Format" data-value="" style="width:85px;">
                        <span class="mw_dropdown_val_holder">
                            <span class="dd_rte_arr"></span>
                            <span class="mw_dropdown_val" style="width: 65px;"><?php _e("Format"); ?></span>
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
                </div>
                <div class="wysiwyyg-cell">
                  <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="<?php _e("Ordered List"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="<?php _e("Unordered List"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_indent" data-command="indent" title="<?php _e("Indent"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_outdent" data-command="outdent" title="<?php _e("Outdent"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="<?php _e("Remove Formatting"); ?>"><span class="ed-ico"></span></span>
                  <span class="mw_editor_btn mw_editor_element" title="<?php _e("Create Draggable Element from selected text."); ?>" data-command="custom-createelement"><span class="ed-ico"></span></span>
                </div>

               <div class="wysiwyyg-cell">
                      <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
                      <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
                      <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
                      <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>
                </div>
                <div class="wysiwyyg-cell">
                      <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span>
                      <span class="mw_editor_btn mw_editor_unlink" data-command="custom-unlink" title="<?php _e("Remove Link"); ?>"><span class="ed-ico"></span></span>
               </div>
              <div class="wysiwyyg-cell">
                 <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_insert" id="wysiwyg_insert" title="<?php _e("Insert"); ?>">
                    <span class="mw_dropdown_val_holder">
                        <span class="dd_rte_arr"></span>
                        <span class="mw_dropdown_val"><?php _e("Insert"); ?></span>
                    </span>
                  <div class="mw_dropdown_fields">
                    <ul>
                      <li value="hr"><a href="#" style="font-size: 10px"><?php _e("Horizontal Rule"); ?></a></li>
                      <li value="box"><a href="#" style="font-size: 10px"><?php _e("Box"); ?></a></li>
                      <li value="table"><a href="#" style="font-size: 10px"><?php _e("Table"); ?></a></li>
                      <?php /*<li value="quote"><a href="#" style="font-size: 10px"><?php _e("Quote"); ?></a></li>*/ ?>
                    </ul>
                  </div>
                </div>
                </div>

                 <?php event_trigger('mw_editor_btn'); ?>

            </div>
        </div>
    </div>


    <script>
        mw.liveEditWYSIWYG = {
          ed:mwd.getElementById('liveedit_wysiwyg'),
          nextBTNS:mw.$(".liveedit_wysiwyg_next"),
          prevBTNS:mw.$(".liveedit_wysiwyg_prev"),
          step:function(){ return  $(mw.liveEditWYSIWYG.ed).width(); },
          denied:false,
          buttons:function(){
            var b = mw.tools.calc.SliderButtonsNeeded(mw.liveEditWYSIWYG.ed);
            if(b.left){
               mw.liveEditWYSIWYG.prevBTNS.show();
            }
            else{
              mw.liveEditWYSIWYG.prevBTNS.hide();
            }
            if(b.right){
               mw.liveEditWYSIWYG.nextBTNS.show();
            }
            else{
              mw.liveEditWYSIWYG.nextBTNS.hide();
            }
          },
          slideLeft:function(){
             if(!mw.liveEditWYSIWYG.denied){
               mw.liveEditWYSIWYG.denied = true;
               var el = mw.liveEditWYSIWYG.ed.firstElementChild;
               var to = mw.tools.calc.SliderPrev(mw.liveEditWYSIWYG.ed, mw.liveEditWYSIWYG.step());
               $(el).animate({left: to}, function(){
                 mw.liveEditWYSIWYG.denied = false;
                 mw.liveEditWYSIWYG.buttons();
               });
             }
          },
          slideRight:function(){
            if(!mw.liveEditWYSIWYG.denied){
               mw.liveEditWYSIWYG.denied = true;
               var el = mw.liveEditWYSIWYG.ed.firstElementChild;
               var to = mw.tools.calc.SliderNext(mw.liveEditWYSIWYG.ed, mw.liveEditWYSIWYG.step());
               $(el).animate({left: to}, function(){
                    mw.liveEditWYSIWYG.denied = false;
                    mw.liveEditWYSIWYG.buttons();
               });
            }
          }
        }
        $(document).ready(function(){
          mw.liveEditWYSIWYG.buttons();
          $(window).bind("resize", function(){
              var n = mw.tools.calc.SliderNormalize(mw.liveEditWYSIWYG.ed);
              if(!!n){
                    mw.liveEditWYSIWYG.slideRight();
              }
          })
        })
    </script>