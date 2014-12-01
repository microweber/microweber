<div id="settings-holder">


  <?php

    $color_scheme = get_option('color-scheme', 'mw-template-liteness');
    if($color_scheme == ''){
      $color_scheme = 'color-white';
    }

    $font = get_option('font', 'mw-template-liteness');

    if($font == ''){
      $font = 'lato';
    }

    $bgimage = get_option('bgimage', 'mw-template-liteness');
    $custom_css_json = get_option('custom_css_json', 'mw-template-liteness');

    $custom_bg =  get_option('custom_bg', 'mw-template-liteness');
    $custom_bg_position = get_option('custom_bg_position', 'mw-template-liteness');
    $custom_bg_size     = get_option('custom_bg_size', 'mw-template-liteness');

  ?>


  <?php if($custom_css_json == ''){ ?>
  <script>CSSJSON = {}</script>
  <?php } else { ?>
  <script>CSSJSON = <?php print $custom_css_json; ?></script>
  <?php } ?>

  <?php
    $selectors = liteness_template_colors_selectors();
    $selectors_js = '';
    foreach($selectors as $name => $selector){
      $selectors_js .= '"'.$name .'":"'.$selector.'",';
    }

    $selectors_js = substr_replace($selectors_js ,"",-1);

  ?>



  <script>
    SELECTORS = { <?php print $selectors_js; ?> };
    CUSTOMBG = "<?php print $custom_bg ?>";
    ExtraPad = function(){
      var scheme = mw.$("#color-scheme-input").val();
      var final = '';
      if(scheme=='transparent'){
        var final = '';
      }
      if(!!CSSJSON['third']){
       if(CSSJSON['third'].toLowerCase() != '#ffffff' && CSSJSON['third'].toLowerCase() != '' && mwd.getElementById('color-scheme-input').value != 'transparent'){
         var final = '.box-container{padding: 20px; }.box-container .box-container{  padding: 0; }';
       }
       if(CSSJSON['third'].toLowerCase() == '#ffffff'){
         var final = '.box-container{padding: 0; }';
       }
      }
      if(!!CSSJSON['fourth']){
          if(CSSJSON['fourth'].toLowerCase() != '#ffffff'){
              var final = '.box-container{padding: 20px; }.box-container .box-container{  padding: 0; }';
          }
      }

      return final;
    };
  </script>


  <link href='//fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,300&subset=latin,cyrillic,cyrillic-ext,greek,latin-ext' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic&subset=latin,cyrillic,greek,latin-ext' rel='stylesheet' type='text/css'>
  <script>

       mw.require("<?php print TEMPLATE_URL;  ?>template_settings.css");

      _body = window.parent.document.body;

      setScheme = function(scheme){
        var csslink = window.parent.document.getElementById('colorscss');
        var url = mw.settings.template_url + 'css/colors/' + scheme + '.css?v='+mw.random();
        csslink.href = url;
      }
      cleanFont = function(){
        mw.$("#font_family li").each(function(){
           var val =  $(this).attr('value');
           $(_body).removeClass(val);
        });
      }
      removeBGImage = function(){
         mw.$(".pick-image").each(function(){
           var val =  $(this).dataset('value');
           $(_body).removeClass(val);
        });
      }
      $(document).ready(function(){
        
          mw.dropdown();
          mw.$(".pick-scheme").click(function(){
            if(!$(this).hasClass('active')){
                mw.$(".pick-scheme").removeClass('active');
                $(this).addClass('active');
                var val = $(this).dataset('value');
                setScheme(val);
                mw.$("#color-scheme-input").val(val).trigger("change");

                /****************************************************
                 Simple Functions to reset one or more custom colors

                    primary('');

                    CleanCSSandJSON();

                    if(!!CSSJSON['third']){
                        third(CSSJSON['third'].replace(/#/g, ''));
                    }

                ****************************************************/
            }
          });

          mw.$(".pick-image").click(function(){
            if(!$(this).hasClass('active')){
                mw.$(".pick-image").removeClass('active');
                $(this).addClass('active');
                var val = $(this).dataset('value');
                removeBGImage();
                $(_body).addClass(val)
                mw.$("#bgimage").val(val).trigger("change");
                if(val=='bgimagecustom'){
                  mw.$("#background-options").show();
                }
                else{
                   mw.$("#background-options").hide();
                }
            }
          });

          mw.$("#font_family").bind("change", function(){
            cleanFont();
            var val = $(this).getDropdownValue();
            _body.className+=' '+val;
            mw.$("#font-input").val(val).trigger("change");
          });





         /******************
           Active Classes
         ******************/

         mw.$(".pick-scheme[data-value='<?php print $color_scheme; ?>']").addClass("active");
         mw.$(".pick-image[data-value='<?php print $bgimage; ?>']").addClass("active");
         mwd.getElementById('color-scheme-input').value = "<?php print $color_scheme; ?>";

         mw.$('#custom_bg').val("<?php print $custom_bg; ?>");
         mw.$('#bgimage').val("<?php print $bgimage; ?>");
         mw.$('#custom_bg_position').val("<?php print $custom_bg_position; ?>");
         mw.$('#custom_bg_size').val("<?php print $custom_bg_size; ?>");
         mw.$("#ts_bg_position .square_map_item[data-value='<?php print $custom_bg_position; ?>']").addClass("active");

        /**********************************************************************

            Universal Color Picker - will be used for all template settings

        ***********************************************************************/



        /* Colors */

        mw.$('[data-func="primary"]').css('background', CSSJSON['primary']);
        mw.$('[data-func="secondary"]').css('background', CSSJSON['secondary']);
        mw.$('[data-func="third"]').css('background', CSSJSON['third']);
        mw.$('[data-func="fourth"]').css('background', CSSJSON['fourth']);
        mw.$('[data-func="fifth"]').css('background', CSSJSON['fifth']);


        Settings = {};
        Settings.tip = mw.tooltip({content:"", element:'.custom-color', position:'top-left'});

        primary = function(a){
           var cTag = parent.mwd.getElementById('customcolorscss');
            SetJSON('primary', '#'+a);
            mw.tools.createStyle(cTag, BuildCSS());
            mw.$('[data-func="primary"]').css('background', '#'+a);
        }
        secondary = function(a){
            var cTag = parent.mwd.getElementById('customcolorscss');
            SetJSON('secondary', '#'+a);
            mw.tools.createStyle(cTag, BuildCSS());
            mw.$('[data-func="secondary"]').css('background', '#'+a);
        }
        third = function(a){
            var cTag = parent.mwd.getElementById('customcolorscss');
            SetJSON('third', '#'+a);
            mw.tools.createStyle(cTag, BuildCSS());
            mw.$('[data-func="third"]').css('background', '#'+a);
        }
        fourth = function(a){
            var cTag = parent.mwd.getElementById('customcolorscss');
            SetJSON('fourth', '#'+a);
            mw.tools.createStyle(cTag, BuildCSS());
            mw.$('[data-func="fourth"]').css('background', '#'+a);
        }

        fifth = function(a){
            var cTag = parent.mwd.getElementById('customcolorscss');
            SetJSON('fifth', '#'+a);
            mw.tools.createStyle(cTag, BuildCSS());
            mw.$('[data-func="fifth"]').css('background', '#'+a);
        }
        UpdateCSS = function(property, value){
            var css = parent.window.mwd.getElementById('customcolorscss');
            SetJSON(property, value);
        }
        BuildCSS = function(){
          var final = '', i;
          final+= SELECTORS["primary_bg"] + '{background-color:' + CSSJSON['primary'] + '}';
          final+= SELECTORS["primary_color"] + '{color:' + CSSJSON['primary'] + '}';
          final+= SELECTORS["secondary_bg"] + '{background-color:' + CSSJSON['secondary'] + '}';
          final+= SELECTORS["secondary_color"] + '{color:' + CSSJSON['secondary'] + '}';

          final+= SELECTORS["third_bg"] + '{background-color:' + CSSJSON['third'] + '}';
          final+= SELECTORS["third_color"] + '{color:' + CSSJSON['third'] + '}';

          final+= SELECTORS["fourth_bg"] + '{background-color:' + CSSJSON['fourth'] + '}';
          final+= SELECTORS["fourth_color"] + '{color:' + CSSJSON['fourth'] + '}';

          final+= SELECTORS["fifth_bg"] + '{background-color:' + CSSJSON['fifth'] + '}';
          final+= SELECTORS["fifth_color"] + '{color:' + CSSJSON['fifth'] + '}';

          final+= ExtraPad();

          return final;
        }

        SaveJSONInt = null;
        SetJSON = function(property, value){
            CSSJSON[property] = value;
            clearTimeout(SaveJSONInt);
            SaveJSONInt = setTimeout(function(){
                mw.$("#custom_css_json").val(JSON.stringify(CSSJSON)).trigger('change');
            }, 500);
        }
        CleanCSSandJSON = function(){
           CSSJSON = {}
           mw.$("#custom_css_json").val('').trigger('change');
           var cTag = parent.mw.$('#customcolorscss').empty();
           mw.$('.pick-custom').removeAttr('style');
        }



        $(Settings.tip).addClass('settings-colorpick').hide();

        Settings.colorPicker = mw.external({
            name:"color_picker",
            holder:  mw.$('.mw-tooltip-content', Settings.tip)[0],
            params:{onlypicker:'true'}
        });
        Settings.colorPickerCallback = function(a){
            mw.external({
                name:"color_picker",
                holder:  mw.$('.mw-tooltip-content', Settings.tip)[0],
                callback:window[a],
                params:{onlypicker:'true'}
            });
        }

        $(Settings.colorPicker).width(235).height(125);
        pickerElement = undefined;
        $(window).bind('scroll', function(){
          if(!!pickerElement){
             mw.tools.tooltip.setPosition(Settings.tip, pickerElement, 'top-left');
          }
        });
        mw.$(".picklabel").click(function(){
           pickerElement = mw.$('.custom-color', this)[0];
           if(!$(pickerElement).hasClass('active')){
               mw.$('.custom-color').removeClass('active');
               $(pickerElement).addClass('active');
               Settings.colorPickerCallback($(pickerElement).dataset('func'));
               mw.tools.tooltip.setPosition(Settings.tip, pickerElement, 'top-left');
               mw.$('iframe', Settings.tip)[0].contentWindow.setColor($(pickerElement).css('backgroundColor'));
               $(Settings.tip).show();
           }
           else{
              mw.$('.settings-colorpick').hide();
              mw.$('.custom-color').removeClass('active');
           }
        });

        $(mwd.body).bind('mousedown', function(e){
           if(!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip-content')
              && !mw.tools.hasClass(e.target, 'pick-custom')
              && !mw.tools.hasClass(e.target, 'mw-tooltip-content')
              && e.target !== mwd.body
              && !mw.tools.hasParentsWithClass(e.target, 'picklabel')){
                    mw.$('.settings-colorpick').hide();
                    mw.$('.custom-color').removeClass('active');
           }
        });

        var uploader = mw.uploader({filetypes:'images', multiple:false});
        mw.$("#upload_custom_body_image").append(uploader);

        $(uploader).bind('FilesAdded', function(){
            mw.$("#image-upload-progress").show();
            mw.$("#upload_custom_body_image").hide();
        });
        $(uploader).bind('progress', function(a,b){
            mw.$("#image-upload-progress .mw-ui-progress-bar").width(b.percent + '%');
        });
        $(uploader).bind('FileUploaded', function(a,b){
          mw.$("#image-upload-progress").hide();
          mw.$("#upload_custom_body_image").show();
          mw.$("#background-options").show();
          mw.$(".pick-image.active").removeClass("active");
          mw.$("#pick-image-custom-body").addClass("active");
          mw.$("#image-upload-progress .mw-ui-progress-bar").width(0);
            mw.$('#custom_bg').val(b.src).trigger('change');
            mw.$('#bgimage').val('bgimagecustom').trigger('change');
            parent.mw.tools.classNamespaceDelete(parent.mwd.body, 'bgimage');
            mw.$("#pick-image-custom-body").css("backgroundImage", 'url(' + b.src + ')').css('visibility', 'visible');
            parent.$(parent.mwd.body).addClass('bgimagecustom');
            parent.mw.$('#custom_bg').empty().html('body.bgimagecustom{background-image:url('+b.src+')}' + ExtraPad());
        });

        mw.$("#ts_bg_position .square_map_item").hover(function(){
          mw.$("#ts_bg_position .square_map_value").html($(this).html());
        });
        mw.$("#ts_bg_position .square_map_item").bind("mousedown", function(){
            mw.$("#ts_bg_position .mw-dropdown-val").html($(this).html())
        });
        mw.$("#ts_bg_position .square_map_item").bind("click", function(){
            if(!$(this).hasClass("active")){
                mw.$("#ts_bg_position .square_map_item").removeClass("active");
                mw.$(this).addClass("active");
                var val = $(this).dataset("value");
                mw.$("#custom_bg_position").val(val).trigger("change");
                if(mw.$('#bgimage').val() == 'bgimagecustom'){
                    var css = 'body.bgimagecustom{background-image:url('+mw.$('#custom_bg').val()+')}body.bgimagecustom{background-position:'+val+';background-size:'+mw.$("#custom_bg_size").val()+';}' + ExtraPad();
                    parent.mw.$('#custom_bg').empty().html(css);
                }
                mw.$("#ts_bg_position .mw-dropdown-val").html($(this).html())
            }
        });

        mw.$("#ts_bg_size").bind("change", function(){
            var val = $(this).getDropdownValue();
            mw.$("#custom_bg_size").val(val).trigger("change");
            var css = 'body.bgimagecustom{background-image:url('+mw.$('#custom_bg').val()+')}body.bgimagecustom{background-size:'+val+';background-position:'+mw.$("#custom_bg_position").val()+';}' + ExtraPad();
            parent.mw.$('#custom_bg').empty().html(css);
        });


        mw.$("#ts_bg_position, #ts_bg_size").bind("click", function(){
            window.scrollTo(0,document.body.scrollHeight);
        });
      });
  </script>
  <script>
    mw.require("files.js");
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
    mw.require("<?php print mw_includes_url(); ?>css/liveedit.css");
  </script>



  <h1><?php _e("Template Settings"); ?></h1>
  <hr>

  <label class="template-setting-label"><?php _e("Font"); ?></label>
  <div title="Template Font" id="font_family" class="mw-dropdown mw-dropdown-default body-class w100">

  <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">
    <span class="mw-dropdown-val"><?php _e("Select"); ?></span>
  </span>
    <div class="mw-dropdown-content">
      <ul>
        <li value="font-arial" ><a style="font-family: Arial" href="#">Arial</a></li>
        <li value="font-verdana" ><a style="font-family: Verdana" href="#">Verdana</a></li>
        <li value="font-lato" ><a style="font-family: Lato" href="#">Lato</a></li>
        <li value="font-georgia"><a style="font-family: Georgia" href="#">Georgia</a></li>
        <li value="font-times"><a style="font-family: 'Times New Roman', Times, serif;" href="#">Times New Roman</a></li>
        <li value="font-robotoslab"><a style="font-family: Roboto Slab" href="#">Roboto Slab</a></li>
        <li value="font-opensans"><a style="font-family: Open Sans" href="#">Open Sans</a></li>
      </ul>
    </div>
  </div>


<hr>
<label class="template-setting-label">Color scheme</label>
<div>
  <a href="javascript:;" class="pick-scheme" style="background-color: #ffffff" data-value='default'></a>
  <a href="javascript:;" class="pick-scheme" style="background-color: #1C659C" data-value='blue'></a>
  <a href="javascript:;" class="pick-scheme" style="background-color: #EB8100" data-value='orange'></a>
  <a href="javascript:;" class="pick-scheme" style="background-color: #8718BD" data-value='purple'></a>
  <a href="javascript:;" class="pick-scheme" style="background-color: #FFA4D5" data-value='pink'></a>
  <a href="javascript:;" class="pick-scheme scheme-transparent" data-value='transparent'></a>

  
</div>


<span class="mw-ui-btn mw-ui-btn-medium right" onclick="CleanCSSandJSON();" style="margin-top: 4px;"><?php _e("Reset"); ?></span>
<label class="template-setting-label ">Custom colors</label>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="primary"></a>
    <label class="desc"><?php _e("Main color"); ?> <small class="muted">( <?php _e("Header, Footer"); ?> )</small></label>
</span>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="fifth"></a>
    <label><?php _e("Buttons & Links"); ?></label>
</span>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="fourth"></a>
    <label><?php _e("Box color"); ?></label>
</span>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="secondary"></a>
    <label><?php _e("Text color"); ?></label>
</span>





<hr>

<label class="template-setting-label"><?php _e("Site Background"); ?></label>

<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="third"></a>
    <label><?php _e("Background color"); ?></label>
</span>

<hr>

<label class="template-setting-label"><?php _e("Background image"); ?></label>

<div class="body-bgs-holder">
  <a href="javascript:;" class="pick-image scheme-transparent" data-value='bgimage0'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage1.png)" data-value='bgimage1'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage2.jpg)" data-value='bgimage2'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage3.jpg)" data-value='bgimage3'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage4.jpg)" data-value='bgimage4'></a>
  <a href="javascript:;"
     class="pick-image pick-image-custom<?php if($bgimage=='bgimagecustom'){ print ' active'; } ?>"
     id="pick-image-custom-body"
     style="background-image: url(<?php print $custom_bg; ?>);<?php if($custom_bg!=''){ print 'visibility:visible;'; } ?>"
     data-value="bgimagecustom">
  </a>





<div id="background-options" style="<?php if($bgimage=='bgimagecustom'){ print 'display:block;'; } ?>">


  <div class="mw-ui-row-nodrop">
    <div class="mw-ui-col">
        <div id="ts_bg_position" class="mw-dropdown mw-dropdown-type-wysiwyg">
          <span class="mw-dropdown_val-holder">
              <span class="mw-dropdown-arrow"></span>
              <span style="width: auto;display: block" class="mw-dropdown-val"><?php _e("Position"); ?></span>
          </span>
          <div class="mw-dropdown-content">
            <ul style="width: 100%">
              <li value="true">
                <div class="square_map">
                    <table align="center" cellspacing="0" cellpadding="2">
                        <tbody>
                            <tr>
                                <td><span data-value="left top" class="square_map_item square_map_item_default"><?php _e("Left Top"); ?></span></td>
                                <td><span data-value="center top" class="square_map_item"><?php _e("Center Top"); ?></span></td>
                                <td><span data-value="right top" class="square_map_item"><?php _e("Right Top"); ?></span></td>
                            </tr>
                            <tr>
                                <td><span data-value="left center" class="square_map_item"><?php _e("Left Center"); ?></span></td>
                                <td><span data-value="center" class="square_map_item"><?php _e("Center"); ?></span></td>
                                <td><span data-value="right center" class="square_map_item"><?php _e("Right Center"); ?></span></td>
                            </tr>
                            <tr>
                                <td><span data-value="left bottom" class="square_map_item"><?php _e("Left Bottom"); ?></span></td>
                                <td><span data-value="center bottom" class="square_map_item"><?php _e("Center Bottom"); ?></span></td>
                                <td><span data-value="right bottom" class="square_map_item"><?php _e("Right Bottom"); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="square_map_value">Left Top</span>
                </div>
              </li>
            </ul>
          </div>
      </div>

    </div>
    <div class="mw-ui-col">
        <div title="Background Size" id="ts_bg_size" class="mw-dropdown mw-dropdown-type-wysiwyg"> <span class="mw-dropdown-val_holder">
            <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val" style="width: auto;display: block"><?php _e("Size"); ?></span> </span>
            <div class="mw-dropdown-content" style="display: none;">
                <ul>
                    <li value="auto"><a href="javascript:;"><?php _e("Auto"); ?></a></li>
                    <li value="contain"><a href="javascript:;"><?php _e("Fit"); ?></a></li>
                    <li value="cover"><a href="javascript:;"><?php _e("Cover"); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
  </div>
</div>

    <span class="mw-ui-btn mw-ui-btn-medium" id="upload_custom_body_image"><?php _e("Upload your image"); ?></span>
  <div class="mw-ui-progress-small" id="image-upload-progress" style="display: none">
      <div style="width: 0%;" class="mw-ui-progress-bar"></div>
  </div>

</div>



<input type="hidden" class="mw_option_field" id="color-scheme-input" name="color-scheme" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="font-input" name="font" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="bgimage" name="bgimage" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="custom_css_json" name="custom_css_json" data-option-group="mw-template-liteness"  />

<input type="hidden" class="mw_option_field" id="custom_bg" name="custom_bg" data-option-group="mw-template-liteness"  />

<input type="hidden" class="mw_option_field" id="custom_bg_position" name="custom_bg_position" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="custom_bg_size" name="custom_bg_size" data-option-group="mw-template-liteness"  />


<input type="hidden" class="mw_option_field" id="kuler_colors" name="kuler_colors" data-option-group="mw-template-liteness"  />





</div>   <!-- /#settings-holder -->