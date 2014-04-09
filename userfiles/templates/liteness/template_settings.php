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
          mw.simpletabs();
          mw.dropdown();
          mw.$(".pick-scheme").click(function(){
            if(!$(this).hasClass('active')){
                mw.$(".pick-scheme").removeClass('active');
                $(this).addClass('active');
                var val = $(this).dataset('value');
                setScheme(val);
                mw.$("#color-scheme-input").val(val).trigger("change");

                third(CSSJSON['third'].replace(/#/g, ''));
            }
          });

          mw.$(".pick-image").click(function(){
            if(!$(this).hasClass('active')){
                mw.$(".pick-image").removeClass('active');
                $(this).addClass('active');
                var val = $(this).dataset('value');
                removeBGImage(val);
                $(_body).addClass(val)
                mw.$("#bgimage").val(val).trigger("change");
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

        /**********************************************************************

            Universal Color Picker - will be used for all template settings

        ***********************************************************************/



        /* Colors */

        mw.$('[data-func="primary"]').css('background', CSSJSON['primary']);
        mw.$('[data-func="secondary"]').css('background', CSSJSON['secondary']);
        mw.$('[data-func="third"]').css('background', CSSJSON['third']);


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

        mw.$(".picklabel").click(function(){
           var _this = mw.$('.custom-color', this)[0];
           if(!$(_this).hasClass('active')){
               mw.$('.custom-color').removeClass('active');
               $(_this).addClass('active');
               Settings.colorPickerCallback($(_this).dataset('func'));
               mw.tools.tooltip.setPosition(Settings.tip, _this, 'top-left');
               mw.$('iframe', Settings.tip)[0].contentWindow.setColor($(_this).css('backgroundColor'));
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

        $(uploader).bind('FileAdded', function(){

        });
        $(uploader).bind('progress', function(){

        });
        $(uploader).bind('FileUploaded', function(a,b){
            mw.$('#custom_bg').val(b.src).trigger('change');
            mw.$('#bgimage').val('bgimagecustom').trigger('change');
            parent.mw.tools.classNamespaceDelete(parent.mwd.body, 'bgimage');
            mw.$("#pick-image-custom-body").css("backgroundImage", 'url(' + b.src + ')').show();
            parent.$(parent.mwd.body).addClass('bgimagecustom');
            parent.mw.$('#custom_bg').empty().html('body.bgimagecustom{background-image:url('+b.src+')}' + ExtraPad());
        });

      });
  </script>
  <script>
    mw.require("files.js");
    mw.require("<?php print INCLUDES_URL; ?>css/wysiwyg.css");
  </script>


  <h1>TEMPLATE SETTINGS</h1>
  <hr>

  <label class="template-setting-label">Font</label>
  <div title="Template Font" id="font_family" class="mw_dropdown mw_dropdown_type_navigation body-class"> <span class="mw_dropdown_val_holder">
    <span class="mw_dropdown_val" style="width: 130px;">Select</span> </span>
    <div class="mw_dropdown_fields" style="left: 0px;">
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

<hr>
<span class="mw-ui-btn mw-ui-btn-medium right" onclick="CleanCSSandJSON();">Reset</span>
<label class="template-setting-label ">Custom colors</label>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="primary"></a>
    <label class="desc">Main color <small class="muted">( Header, Footer &amp; buttons )</small></label>
</span>
<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="secondary"></a>
    <label>Text color</label>
</span>




<hr>

<label class="template-setting-label">Site Background</label>

<span class="picklabel">
    <a href="javascript:;" class="pick-custom custom-color scheme-transparent" data-func="third"></a>
    <label>Background color</label>
</span>

<hr>

<label class="template-setting-label">Backgroud image</label>

<div>
  <a href="javascript:;" class="pick-image scheme-transparent" data-value='bgimage0'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage1.jpg)" data-value='bgimage1'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage2.jpg)" data-value='bgimage2'></a>
  <a href="javascript:;" class="pick-image" style="background-image: url(<?php print TEMPLATE_URL;  ?>img/bgimage3.jpg)" data-value='bgimage3'></a>
  <a href="javascript:;"
     class="pick-image pick-image-custom"
     id="pick-image-custom-body"
     style="background-image: url(<?php print $custom_bg; ?>);<?php if($custom_bg!=''){ print 'display:inline-block;'; } ?>"
     data-value="bgimagecustom">
  </a>
  <span class="mw-ui-btn mw-ui-btn-medium" id="upload_custom_body_image">Upload your image</span>
</div>




<input type="hidden" class="mw_option_field" id="color-scheme-input" name="color-scheme" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="font-input" name="font" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="bgimage" name="bgimage" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="custom_css_json" name="custom_css_json" data-option-group="mw-template-liteness"  />
<input type="hidden" class="mw_option_field" id="custom_bg" name="custom_bg" data-option-group="mw-template-liteness"  />





</div>   <!-- /#settings-holder -->