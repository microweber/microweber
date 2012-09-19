<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<?php /*<script src="<?php   print( INCLUDES_URL);  ?>js/jquery.js" type="text/javascript"></script>*/ ?>

<script src="<?php   print( SITE_URL);  ?>apijs" type="text/javascript"></script>
<script src="<?php   print( INCLUDES_URL);  ?>js/jquery-ui-1.8.20.custom.js" type="text/javascript"></script>
<?php /* <script src="http://code.jquery.com/ui/jquery-ui-git.js" type="text/javascript"></script> */ ?>
<script src="<?php   print( INCLUDES_URL);  ?>js/edit_libs.js" type="text/javascript"></script>

<link href="<?php   print( INCLUDES_URL);  ?>api/api.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/mw_framework.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/toolbar.css" rel="stylesheet" type="text/css" />


 <script src="<?php   print( INCLUDES_URL);  ?>js/sortable.js?v=<?php echo uniqid(); ?>" type="text/javascript"></script>
<?php /* <script src="http://c9.io/ooyes/mw/workspace/sortable.js" type="text/javascript"></script>  */ ?>
<script src="<?php   print( INCLUDES_URL);  ?>js/toolbar.js?v=<?php echo uniqid(); ?>" type="text/javascript"></script>
<script type="text/javascript">



        $(document).ready(function () {

           mw.drag.create();


            mw.history.init();
            mw.tools.module_slider.init();
            mw.tools.dropdown();
            mw.tools.toolbar_tabs.init();
            mw.tools.toolbar_slider.init();



        });

		
		
		
		
		
		
		
		
		
		
		
		
		    $(document).ready(function () {
		
		
		


 $(".mw_option_field").live("change blur", function () {
                var refresh_modules11 = $(this).attr('data-refresh');
				
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).attr('data-reload');

				}
				
					
				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
 refresh_modules11 = '#'+refresh_modules11;
				}
				
				 
				

				og = $(this).attr('data-module-id');
				if(og == undefined){
					og = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module') 
				}
                $.ajax({

                    type: "POST",
                    url: "<? print site_url('api/save_option') ?>",
                    data: ({

                        option_key: $(this).attr('name'),
                        option_group: og,
                        option_value: $(this).val()


                    }),
                    success: function () {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()

                            if (window.mw != undefined) {
                                if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(refresh_modules11);
                                }
                            }
  
                        }

                        //  $(this).addClass("done");
                    }
                });
            });
		
		    });

    </script>

    <span id="show_hide_sub_panel" onclick="mw.toggle_subpanel();"><span id="show_hide_sub_panel_slider"></span><span id="show_hide_sub_panel_info">Hide</span></span>

<div class="mw" id="live_edit_toolbar_holder">
  <div class="mw" id="live_edit_toolbar">
    <div id="mw_toolbar_nav"> <a href="<?php print site_url(); ?>" id="mw_toolbar_logo">Microweber - Live Edit</a>



    <?php /* <a href="javascript:;" style="position: absolute;top: 10px;right: 10px;" onclick="mw.extras.fullscreen(document.body);">Fullscreen</a> */  ?>





      <ul id="mw_tabs">
        <li> <a href="#mw_tab_modules"><? _e('Modules'); ?></a> </li>
        <li> <a href="#mw_tab_layouts"><? _e('Layouts'); ?></a> </li>
       
        <li> <a href="#mw_tab_design"><? _e('Design'); ?></a> </li>
         <li> <a href="#tab_pages"><? _e('Pages'); ?></a> </li>
        <li> <a href="#mw_tab_help"><? _e('Help'); ?></a> </li>
       </ul>
    </div>
    <div id="tab_modules" class="mw_toolbar_tab">
        <microweber module="admin/modules/categories_dropdown" />
      <div class="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
      <div class="mw_clear">&nbsp;</div>
    </div>
    <div id="tab_layouts" class="mw_toolbar_tab">
       
      <microweber module="admin/modules/categories_dropdown" data-for="elements" />
      <div class="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list_elements" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
    </div>
    <div id="tab_design" class="mw_toolbar_tab">

     <div class="mw_dropdown mw_dropdown_type_navigation left" id="module_design_selector"> <span class="mw_dropdown_val">Picture Editor</span>
        <div class="mw_dropdown_fields">
          <ul>
            <li value="#tb_el_style"><a href="javascript:;">Site style</a></li>
            <li value="#tb_image_edit"><a href="javascript:;">Picture Editor</a></li>
          </ul>
        </div>
      </div>


      <div id="tb_design_holder">


          <div class="tb_design_tool" id="tb_image_edit"></div>

          <div class="tb_design_tool" id="tb_el_style">





            <div class="ed_style_all ed_style_margin_padding">

                <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="margin_selector" data-for="margin_slider">
                    <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">Margin</span>
                    </span>

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
                                <span class="square_map_value">&nbsp;</span>
                            </div>
                        </li>
                      </ul>
                    </div>
                </div>
                <div class="ed_slider margin-slider es_item left" id="margin_slider" data-type="margin"></div>
                <div class="mw_clear"></div>


                <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="padding_selector" data-for="padding_slider">
                    <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">Padding</span>
                    </span>

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
                                <span class="square_map_value">&nbsp;</span>
                            </div>
                        </li>
                      </ul>
                    </div>
                </div>
                <div class="ed_slider padding-slider es_item left" id="padding_slider" data-type="padding"></div>



          </div>

          <span class="mw_dlm mw_dlm_style"></span>

          <div class="ed_style_all" style="padding-top: 0">


              <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_fx" id="fx_element" title="Effects" data-value="">
                  <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">F<b>x</b></span>
                  </span>
                <div class="mw_dropdown_fields">
                  <ul>
                    <li value="-1"><a href="javascript:;">Choose F<b>x</b></a></li>
                    <li value="shadow"><a href="javascript:;">Drop Shadow</a></li>
                    <li value="perspective"><a href="javascript:;">Perspective</a></li>
                    <li value="opacity"><a href="javascript:;">Opacity</a></li>
                    <li value="radius"><a href="javascript:;">Radius</a></li>
                    <li value="sepia"><a href="javascript:;" onclick="$('.element-current').addClass('sepia')">Sepia</a></li>
                    <li value="grayscale"><a href="javascript:;" onclick="$('.element-current').addClass('grayscale')">Grayscale</a></li>
                  </ul>
                </div>
              </div>
              <div id="element_effx" class="clear" style="padding: 0px 0 0 13px;">
                  <div class="fx" id="fx_shadow">

                    <span class="ed_label left">Position</span><div id="ed_shadow" class="fx_canvas_slider left" style="width: 40px;height: 40px;"></div>

                    <span class="mw_dlm" style="height: 40px;margin-top: 0"></span>

                    <span class="ed_label left">Blur</span><div id="ed_shadow_strength" class="fx_canvas_slider left" style="width: 30px;height: 9px;background-image: none"></div>

                    <span class="mw_dlm" style="height: 40px;margin-top: 0"></span>

                    <span class="ed_item ed_color_pick ed_shadow_color left" data-color="696969" onclick="mw.wysiwyg.request_change_shadow_color(this);"><span></span></span>

                  </div>
                  <div class="fx" id="fx_perspective">
                       <div class="ed_slider perspective-slider"></div>
                  </div>

               <div class="fx" id="fx_opacity">
                  <div class="ed_slider opacity-slider es_item" data-type="opacity"></div>
               </div>
               <div class="fx" id="fx_radius">
                  <div class="ed_slider radius-slider es_item" data-type="border-radius"></div>
               </div>
              </div>
            </div>

            <span class="mw_dlm mw_dlm_style"></span>



           <div class="ed_style_all">


            <span class="ed_label left">Width</span>
            <div class="ed_slider width-slider es_item" id="width_slider" data-max="1000" data-min="100" data-type="width"></div>

            <div class="mw_clear" style="padding-bottom: 5px;"></div>


            <div class="mw_dropdown mw_dropdown_type_wysiwyg" style="margin-left: -5px;" id="align_element" title="Align" data-value="none" onchange="mw.alignem($(this).getDropdownValue());">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Align</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="none"><a href="javascript:;">None</a></li>
                  <li value="left"><a href="javascript:;">Left</a></li>
                  <li value="right"><a href="javascript:;">Right</a></li>
                  <li value="center"><a href="javascript:;">Center</a></li>

                </ul>
              </div>
            </div>


             </div>

            <span class="mw_dlm mw_dlm_style"></span>

            <div class="ed_style_all">
             <span class="ed_label left">Background color</span>
            <span class="ed_item ed_color_pick left" onclick="mw.wysiwyg.request_change_bg_color(this);"><span></span></span>

            <div class="mw_clear" style="padding-bottom: 7px;">&nbsp;</div>

            <a href="javascript:;" class="ed_btn left" onclick="mw.wysiwyg.image('#background_image');">Background Image</a>


            <div class="mw_dropdown mw_dropdown_type_wysiwyg" style="margin-left: -5px;" id="align_element" title="Align" data-value="none" onchange="$('.element-current').css('backgroundRepeat', $(this).getDropdownValue())">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Background repeat</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="no-repeat"><a href="javascript:;">None</a></li>

                  <li value="repeat-x"><a href="javascript:;">Repeat Horizontally</a></li>
                  <li value="repeat-y"><a href="javascript:;">Repeat Vertically</a></li>
                  <li value="repeat"><a href="javascript:;">Repeat Both</a></li>
                </ul>
              </div>
            </div>



            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="bg_pos_selector" onchange="$('.element-current').css('backgroundPosition', $(this).getDropdownValue())">
                    <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">Background Position</span>
                    </span>

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
                                <span class="square_map_value">&nbsp;</span>
                            </div>
                        </li>
                      </ul>
                    </div>
                </div>



            </div>

            <span class="mw_dlm mw_dlm_style"></span>


            <div class="ed_style_all">



              <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_func_slider left" id="border_position_selector" data-value="border" onchange="mw.border_which=$(this).getDropdownValue();">
                    <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">Border Position</span>
                    </span>

                    <div class="mw_dropdown_fields">
                      <ul style="width: 100%">
                        <li value="true">
                            <div class="square_map">
                                <table cellpadding="2" cellspacing="0" align="center">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><span class="square_map_item" data-value="border-top">Top</span></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><span class="square_map_item" data-value="border-left">Left</span></td>
                                        <td><span class="square_map_item square_map_item_default active" data-value="border">All</span></td>
                                        <td><span class="square_map_item" data-value="border-right">Right</span></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><span class="square_map_item" data-value="border-bottom">Bottom</span></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <span class="square_map_value">&nbsp;</span>
                            </div>
                        </li>
                      </ul>
                    </div>
                </div>


            <div class="mw_dropdown mw_dropdown_type_wysiwyg" style="margin-left: -5px;"  title="Border Style" data-value="solid" onchange="$('.element-current').css(mw.border_which+'Style', $(this).getDropdownValue())">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Border Style</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="solid"><a href="javascript:;">Solid</a></li>
                  <li value="dotted"><a href="javascript:;">Dotted</a></li>
                  <li value="dashed"><a href="javascript:;">Dashed</a></li>
                  <li value="inset"><a href="javascript:;">Inset</a></li>
                  <li value="outset"><a href="javascript:;">Outset</a></li>
                  <li value="double"><a href="javascript:;">Double</a></li>
                </ul>
              </div>
            </div>
            <div class="mw_dropdown mw_dropdown_type_wysiwyg" style="margin-left: -5px;"  title="Border Width" data-value="0" onchange="$('.element-current').css(mw.border_which+'Width', $(this).getDropdownValue())">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Border Width</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="1px"><a href="javascript:;">1px</a></li>
                  <li value="2px"><a href="javascript:;">2px</a></li>
                  <li value="3px"><a href="javascript:;">3px</a></li>
                  <li value="4px"><a href="javascript:;">4px</a></li>
                  <li value="5px"><a href="javascript:;">5px</a></li>
                  <li value="6px"><a href="javascript:;">6px</a></li>
                  <li value="7px"><a href="javascript:;">7px</a></li>
                  <li value="8px"><a href="javascript:;">8px</a></li>
                  <li value="9px"><a href="javascript:;">9px</a></li>
                  <li value="10px"><a href="javascript:;">10px</a></li>
                </ul>
              </div>
            </div>

                <span class="ed_item ed_color_pick ed_bordercolor_pick" onclick="mw.wysiwyg.request_border_color(this);"><span></span></span>

            </div>

            <span class="mw_dlm mw_dlm_style"></span>

          </div>


      </div>


    </div>
    
     <div id="tab_pages" class="mw_toolbar_tab"> 
      <? include(INCLUDES_DIR.'admin'.DS.'content.php') ?>
     </div>
    
    
    <div id="tab_help" class="mw_toolbar_tab">Help</div>
    <div id="tab_style_editor" class="mw_toolbar_tab">
      <? //include( 'toolbar_tag_editor.php') ; ?>
    </div>
    <div id="mw-text-editor" class="mw_editor">
        <div class="editor_wrapper">
            <span class="mw_editor_btn mw_editor_undo" data-command="undo" title="Undo"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_redo" data-command="redo" title="Redo"><span class="ico"></span></span>
            <span class="mw_dlm"></span>
            <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="Bold"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="Italic"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="Underline"><span class="ico"></span></span>

            <span class="mw_editor_btn mw_editor_font_color" data-command="custom-fontcolorpicker" title="Font Color"><span class="ico"></span></span>

            <span class="mw_editor_btn mw_editor_font_background_color" data-command="custom-fontbgcolorpicker" title="Font Background Color"><span class="ico"></span></span>

            <span class="mw_dlm"></span>



            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_format" id="format_main" title="Format" data-value="">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Format</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="h1"><a href="#">Heading 1</a></li>
                  <li value="h2"><a href="#">Heading 2</a></li>
                  <li value="h3"><a href="#">Heading 3</a></li>
                  <li value="h4"><a href="#">Heading 4</a></li>
                  <li value="h5"><a href="#">Heading 5</a></li>
                  <li value="h6"><a href="#">Heading 6</a></li>
                  <li value="p"><a href="#">Paragraph</a></li>
                </ul>
              </div>
            </div>



            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_family" id="font_family_selector_main" title="Font" data-value="Arial">
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




            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_size" id="font_size_selector_main" title="Font Size">

                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">10pt</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="1"><a href="#">8pt</a></li>
                  <li value="2"><a href="#">10pt</a></li>
                  <li value="3"><a href="#">12pt</a></li>
                  <li value="4"><a href="#">14pt</a></li>
                  <li value="5"><a href="#">18pt</a></li>
                  <li value="6"><a href="#">24pt</a></li>
                  <li value="7"><a href="#">36pt</a></li>
                </ul>
              </div>
            </div>



            <span class="mw_dlm"></span>


            <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="Ordered List"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="Unordered List"><span class="ico"></span></span>

            <span class="mw_editor_btn mw_editor_indent" data-command="indent" title="Indent"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_outdent" data-command="outdent" title="Outdent"><span class="ico"></span></span>



            <span class="mw_dlm"></span>

            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft" data-command="justifyLeft" title="Align Left"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter" data-command="justifyCenter" title="Align Center"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright" data-command="justifyRight" title="Align Right"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull" data-command="justifyFull" title="Align Both Sides"><span class="ico"></span></span>


            <span class="mw_dlm"></span>


            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="Add/Edit/Remove Link"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_image" data-command="custom-image" title="Add/Edit/Remove Image"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="Remove Formatting"><span class="ico"></span></span>



            <span class="mw_dlm"></span>



            <span class="mw_editor_btn mw_editor_element" data-command="custom-createelement"><span class="ico"></span></span>
        </div>




    </div>


     <span class="mw_editor_btnz ed_btn" onclick="mw.drag.save()"
        style="position: fixed;top: 133px;right:30px; z-index: 2000;">Save</span>




    <?php /*  THE SMALL EDITOR  */ " Starts Here " ?>

    <div id="mw_small_editor">
        <div class="mw_small_editor_top">
            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_family" id="font_family_selector_small" title="Font" data-value="Arial">
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
            <span class="mw_dlm"></span>
            <div class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_action_font_size" id="font_size_selector_small" title="Font Size">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">10pt</span>
                </span>
              <div class="mw_dropdown_fields">
                <ul>
                  <li value="1"><a href="#">8pt</a></li>
                  <li value="2"><a href="#">10pt</a></li>
                  <li value="3"><a href="#">12pt</a></li>
                  <li value="4"><a href="#">14pt</a></li>
                  <li value="5"><a href="#">18pt</a></li>
                  <li value="6"><a href="#">24pt</a></li>
                  <li value="7"><a href="#">36pt</a></li>
                </ul>
              </div>
            </div>
            <span class="mw_dlm"></span>
            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="Add/Edit/Remove Link"><span class="ico"></span></span>
            <span class="mw_editor_btn mw_editor_image" data-command="custom-image" title="Add/Edit/Remove Image"><span class="ico"></span></span>
            <div class="mw_clear">&nbsp;</div>
        </div>
        <div class="mw_small_editor_bottom">
          <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="Bold"><span class="ico"></span></span>
          <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="Italic"><span class="ico"></span></span>
          <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="Underline"><span class="ico"></span></span>
          <span class="mw_dlm"></span>
          <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft" data-command="justifyLeft" title="Align Left"><span class="ico"></span></span>
          <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter" data-command="justifyCenter" title="Align Center"><span class="ico"></span></span>
          <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright" data-command="justifyRight" title="Align Right"><span class="ico"></span></span>
          <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull" data-command="justifyFull" title="Align Both Sides"><span class="ico"></span></span>
          <div class="mw_clear">&nbsp;</div>
        </div>

    </div><!-- /mw_small_editor -->


    <div id="mw-history-panel"></div>
    <div id="mw-saving-loader"></div>
  </div>




  <!-- /end .mw -->
</div>
<!-- /end mw_holder -->
<span class="mw_editor_btnz" onclick="$('.mw_modal iframe').each(function(){var src = this.src;this.src = '#';this.src =src});"
        style="color:#fff;cursor:pointer;display: inline-block;padding: 5px 10px;background: #6D7983;box-shadow:0 0 5px #ccc;position: fixed;top: 130px;right:130px; z-index: 92000;display: none">Refresh iframes &reg;</span>