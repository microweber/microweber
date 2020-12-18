<div id="image_settings_modal_holder">

    <link rel="stylesheet" href="<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.css" type="text/css"/>
    <script src="<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.js"></script>
    <script src="<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/jquery-cropper.min.js"></script>

    <style scoped="scoped">

        #the-image-holder {
            position: relative;
            text-align: center;
            max-width: 100%;
            max-height: 300px;
            direction: ltr !important;
        }

        #mwimagecurrentoverlay{
            position: absolute;
            display: block;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color:rgba(0, 0, 0, 0.2);
            pointer-events: none;
            opacity: 0;
        }

        #the-image-holder img {
            max-width: 100%;
            max-height: 300px;
            box-shadow: 0 0 4px -2px #000;
            -webkit-box-shadow: 0 0 4px -1px #000;
        }
        .nav-actions{
            float: right;
        }

        @media (max-width:550px){
            .nav-actions{
                clear: both;
                float: none;
                padding-top: 20px;
                display: block;
            }
            .imeditor-image-description,
            .imeditor-image-description > div.mw-ui-col{
                display: block;
                padding-top: 10px;

            }
            .imeditor-image-description > div.mw-ui-col > div{
                padding-left: 0px;
                padding-right: 0px;
            }
        }

        #mwimagecurrent{
            display: block;
            margin: auto;
        }

        #background-properties{
            clear: both;
            padding-top: 12px;
        }

        .s-field select{
            width: 170px;
        }
        .s-field + .s-field{
            padding-top: 10px;
        }



    </style>

    <script>
        mw.require('css_parser.js');
        // mw.require('color.js', 'color_js');
        // mw.require('color.js');
        mw.lib.require('colorpicker');

        mw.require("files.js");
    </script>

    <div class='image_settings_modal'>


        <div id="the-image-holder"><!-- Image will be placed here --></div>
        <div class="mw-ui-box-content">
            <div style="text-align:center;padding-bottom: 12px;">
                <div id="cropmenu" class="mw-ui-btn-nav" style="display: none;">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info" onclick="DoCrop()"><?php _e("Crop"); ?></span>
                    <span class="mw-ui-btn mw-ui-btn-medium" onclick="cropcancel()"><?php _e("Cancel"); ?></span>
                </div>
            </div>
            <div class="mw-ui-field-holder" id="edititems">
            <div class="mw-ui-field-holder" style="padding-bottom: 20px;" id="editmenu">
                <div class="mw-ui-btn-nav pull-left" style="margin-right:12px">


                  <span class="mw-ui-btn" onclick="createCropTool();">
                    <span class="mw-icon-crop"></span> &nbsp;<?php _e('Crop') ?>
                  </span>
                  <span class="mw-ui-btn mw-ui-btn-icon"
                        onclick="mw.image.rotate(mw.image.current);mw.image.current_need_resize = true;mw.$('#mw_image_reset').removeClass('disabled')">
                    <span class="mw-icon-app-refresh-empty"></span> &nbsp; <?php _e('Rotate'); ?>
                  </span>

                </div>


                <div class="mw-dropdown mw-dropdown-default pull-left" id="" style="width: 140px;">
                    <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val"><?php _e("Effects"); ?></span>
                    <div class="mw-dropdown-content" style="display: none;">
                        <ul>
                            <li value="vintage" onclick="mw.image.vintage(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><?php _e("Vintage Effect"); ?></a></li>
                            <li value="grayscale" onclick="mw.image.grayscale(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><?php _e("Grayscale"); ?></li>
                        </ul>
                    </div>
                </div>

                <div id="background-properties" style="display: none;">
                        <div class="s-field">
                            <label><?php _e("Size"); ?></label>
                            <div class="s-field-content">
                                <div class="mw-field" data-size="medium">
                                    <select type="text" class="regular" data-prop="backgroundSize">
                                        <option value="auto"><?php _e("Auto"); ?></option>
                                        <option value="contain"><?php _e("Fit"); ?></option>
                                        <option value="cover"><?php _e("Cover"); ?></option>
                                        <option value="100% 100%">Scale</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="s-field">
                            <label><?php _e("Position"); ?></label>
                            <div class="s-field-content">
                                <div class="mw-field" data-size="medium">
                                    <select type="text" class="regular" data-prop="backgroundPosition">
                                        <option value="0% 0%"><?php _e("Left Top"); ?></option>
                                        <option value="50% 0%"><?php _e("Center Top"); ?></option>
                                        <option value="100% 0%"><?php _e("Right Top"); ?></option>

                                        <option value="0% 50%"><?php _e("Left Center"); ?></option>
                                        <option value="50% 50%"><?php _e("Center Center"); ?></option>
                                        <option value="100% 50%"><?php _e("Right Center"); ?></option>

                                        <option value="0% 100%"><?php _e("Left Bottom"); ?></option>
                                        <option value="50% 100%"><?php _e("Center Bottom"); ?></option>
                                        <option value="100% 100%"><?php _e("Right Bottom"); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                </div>


            </div>


            <div class="mw-ui-field-holder" style="padding-bottom: 20px;display: none" id="overlayholder">
                <label class="mw-ui-label"><?php _e('Overlay color'); ?></label>
                <input type="text" class="mw-ui-field w100" id="overlaycolor" placeholder="Enter color"/>

                <script>

                    isBG = false;


                  CurrSRC = function(b){
                    var curr = parent.mw.image.currentResizing ? parent.mw.image.currentResizing[0] : new Image();
                    if(curr.nodeName == 'IMG'){
                      if(!b){
                        return curr.src;
                      }
                      else{
                        curr.src = b
                      }
                    }
                    else{
                        isBG = {};
                      if(!b){

                        return mw.CSSParser(curr)
                              .get
                              .background()
                              .image
                              .trim()
                              .split('url(')[1]
                              .split(')')[0]
                              .trim()
                              .split('"')
                              .join('');
                      }
                      else{

                        curr.style.backgroundImage = 'url('+mw.files.safeFilename(b)+')';
                        mw.top().wysiwyg.bgQuotesFix(curr);
                        //mw.top().trigger('nodeBackgroundChanged', [curr, b])
                      }
                    }
                  }

                  setColor = function(save){
                      var color = $("#overlaycolor").val();
                      var alpha = parseInt($("#overlaycoloralpha").val(), 10);
                      if(isNaN(alpha)){
                        alpha = 100;
                      }
                      alpha = (alpha/100);
                      var final = mw.color.hexToRgbaCSS(color, alpha);
                      if(save){
                        $(".mw-image-holder-overlay", SelectedImage.parentNode).css('backgroundColor', final);
                      }

                      $("#mwimagecurrentoverlay").css('backgroundColor', final)
                  };

                  $(document).ready(function(){

                      mw.top().on('imageSrcChanged', function(e, node, url){
                        if(url != $('#mwimagecurrent')[0].src){
                            $('#mwimagecurrent')[0].src = url;
                        }
                      });


                  if (self !== parent && parent.mw.image.currentResizing) {
                      SelectedImage = parent.mw.image.currentResizing[0];
                  }
                  else if (self !== parent && parent.mw.image.currentResizing) {

                      SelectedImage = parent.mw.$('.element-current')[0];
                  }

                      if(!window.SelectedImage){
                          SelectedImage = new Image();
                      }

                      if(SelectedImage.nodeName != 'IMG'){
                          $(".imeditor-image-description,.imeditor-image-link").remove()
                      }


                    if(isImageHolder()){
                      $("#overlayholder, #alphaholder").show();
                      currentOverlay = $('.mw-image-holder-overlay',  SelectedImage.parentNode);
                      var currentOverlayColor = mw.CSSParser(currentOverlay[0]).css.backgroundColor || 'rgba(0,0,0,0)';
                      currentOverlayColorParse = mw.color.colorParse(currentOverlayColor);

                      $("#overlaycolor").val(mw.color.rgbToHex(currentOverlayColorParse))
                      $("#overlaycoloralpha").val(currentOverlayColorParse.alpha * 100)

                      previewbg = 'rgba(' + currentOverlayColorParse.r + ',' + currentOverlayColorParse.g + ',' + currentOverlayColorParse.b + ',' + currentOverlayColorParse.alpha + ')';


                    }
                    pick3 = mw.colorPicker({
                      element:'#overlaycolor',
                      onchange:function(color){
                        $("#overlaycolor").val(color);
                        setColor()
                      }
                    });

                    $(".mw-ui-btn-change-image").on('click', function(e){
                      mw.top().wysiwyg.media('#editimage', e.target);
                      if(window.thismodal){
                          thismodal.remove()
                      }


                    })
                  })
                </script>
            </div>
            <div class="mw-ui-field-holder" style="padding-bottom: 20px;display: none" id="alphaholder">
                <label class="mw-ui-label"><?php _e('Overlay alpha'); ?></label>
                <input type="range" min="0" max="100" id="overlaycoloralpha" onchange="setColor()" />
            </div>
            <div class="mw-ui-field-holder imeditor-image-link" style="padding-bottom: 20px;">
                <label class="mw-ui-label"><?php _e('Links to:'); ?></label>
                <input type="text" class="mw-ui-field w100" id="link" placeholder="Enter URL"/>
            </div>

            <div class="mw-ui-row-nodrop imeditor-image-description" style="padding-bottom: 20px;">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <label class="mw-ui-label"><?php _e("Image Description"); ?></label>
                        <textarea class="mw-ui-field w100" placeholder='<?php _e("Enter Description"); ?>' id="image-title"></textarea>
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <label class="mw-ui-label">
                            <?php _e("Image Alternative Text"); ?>
                            <span
                                    class="mw-icon-help-outline mwahi tip"
                                    data-tipposition="top-center"
                                    data-tip="<?php _e("Text that appears if image fails to load. (Important for Search Engines)"); ?>">

                            </span>
                        </label>
                        <textarea class="mw-ui-field w100" placeholder='<?php _e("Enter Description"); ?>' id="image-alt"></textarea>
                    </div>
                </div>
            </div>
            </div>
            <div class="mw-ui-btn-nav nav-actions">



                <span class="mw-ui-btn disabled" id="mw_image_reset"><?php _e("Reset"); ?></span>


                <span class='mw-ui-btn mw-ui-btn-info mw-ui-btn-savetheimage'><?php _e("Update"); ?></span>
            </div>
        </div>
    </div>
</div>

<script>


  isImageHolder = function(){
      if(!parent.mw.image.currentResizing) return false;
    return mw.tools.hasClass(parent.mw.image.currentResizing[0].parentNode, 'mw-image-holder')
  }


    var createCropTool = function () {
        mw.$('#cropmenu').show();
        mw.$('#editmenu').hide();
        cropImage = $('#mwimagecurrent');
        cropImage.cropper({
            crop: function (data) {
              mw.$('.cropper-dragger', cropImage[0].parentNode).bind('dblclick', function () {
                  DoCrop();
              });
            }
        });
        $('.mw-ui-btn-nav.nav-actions').hide();
        $('#edititems').hide()
    }


    DoCrop = function () {
        var data = cropImage.cropper("getData");
        var canvas = document.createElement('canvas');
        canvas.width = data.width,
        canvas.height = data.height;
        var context = canvas.getContext('2d');
        context.drawImage(cropImage[0], data.x, data.y, data.width, data.height, 0, 0, data.width, data.height);
        var newsrc = canvas.toDataURL();
        var newimg = new Image();
        newimg.src = newsrc;
        newimg.id = 'mwimagecurrent';
        mw.$(".cropper-container", cropImage[0].parentNode).remove();
        cropImage.replaceWith(newimg);
        mw.image.current = newimg;
        mw.$('#cropmenu').hide();
        mw.$('#editmenu').show();

        $('.mw-ui-btn-nav.nav-actions').show();
        $('#edititems').show()

    }
    cropcancel = function () {

        mw.$(".cropper-container").remove();
        mw.$('#cropmenu').hide();
        mw.$('#editmenu').show();
        var newimg = new Image();
        newimg.src = cropImage.attr('src');
        newimg.id = 'mwimagecurrent';
        cropImage.replaceWith(newimg);

        $('.mw-ui-btn-nav.nav-actions').show();
        $('#edititems').show()

    }

    $(mwd).ready(function () {




        if (mw.tools.hasParentsWithTag(SelectedImage, 'a')) {


            $("#link").val($(mw.tools.firstParentWithTag(SelectedImage, 'a')).attr("href"));
        }


        mw.image.current_need_resize = false;

        var src = CurrSRC(),
            title = SelectedImage.title,
            alt = SelectedImage.alt;
        mw.$("#the-image-holder").html("<img id='mwimagecurrent' src='" + src + "' /><span id='mwimagecurrentoverlay'></span>");

        if(isBG){
            mw.$('#background-properties')
                .show()
                .find('select')
                .on('change input', function () {
                    isBG[this.dataset.prop] = $(this).val()
                });
        }

         if(!!window.previewbg){
          $("#mwimagecurrentoverlay").css('backgroundColor', previewbg)
         }


        mw.image.current_original = src;

        mw.image.current = mwd.querySelector("#mwimagecurrent");
        if (typeof title !== "undefined") mw.$("#image-title").val(title);
        if (typeof alt !== "undefined") mw.$("#image-alt").val(alt);
        //if(!!title)mw.$("#image-title").val(title);
        //if(!!alt)mw.$("#image-alt").val(alt);

        mw.$(".mw-ui-btn-savetheimage").on('click', function () {

            mw.top().wysiwyg.change(SelectedImage);
            if(isBG) {
                $(SelectedImage).css(isBG);
            }
            CurrSRC(mw.image.current.src);
            if (typeof mw.image.current_align !== "undefined"){
            //if(!!mw.image.current_align){
                SelectedImage.align = mw.image.current_align;
            }
            if (typeof SelectedImage.title !== "undefined"){
            //if(!!SelectedImage.title){
                SelectedImage.title = mw.$("#image-title").val();
            }
            if (typeof SelectedImage.alt !== "undefined"){
            //if(!!SelectedImage.alt){
                SelectedImage.alt = mw.$("#image-alt").val();
            }
            SelectedImage.style.height = '';
            if (mw.image.current_need_resize && SelectedImage.nodeName === 'IMG') {
                mw.image.preload(mw.image.current.src, function (w, h) {
                    SelectedImage.style.width = w + 'px';
                    SelectedImage.style.height = 'auto';
                    // parent.mw.wysiwyg.normalizeBase64Image(theImage);
                    if(window.thismodal) {
                        thismodal.remove()
                    }

                });
            }

            parent.mw.wysiwyg.normalizeBase64Image(SelectedImage);

            var link_url = $("#link").val();
            if (link_url == ""){
                $(SelectedImage).unwrap('a');
            } else {
                link_url = link_url.trim();
                if (mw.tools.hasParentsWithTag(SelectedImage, 'a')) {
                    $(mw.tools.firstParentWithTag(SelectedImage, 'a')).attr("href", link_url);
                }
                else {
                    $(SelectedImage).wrap('<a href="' + link_url + '"></a>');
                }
            }

            setColor(true);

           // alert(parent.mw.tools.firstParentWithClass(SelectedImage, 'edit'));


            if(parent.mw.tools.hasParentsWithClass(SelectedImage, 'edit')){
            parent.mw.wysiwyg.change(parent.mw.tools.firstParentWithClass(SelectedImage, 'edit'));
            }
            mw.top().$(window.top).trigger('imageSrcChanged', [SelectedImage, CurrSRC()])

            if(window.thismodal){
                thismodal.remove()
            }



        });

        mw.$("#mw_image_reset").click(function () {
            if (!$(this).hasClass("disabled")) {
                mw.image.current.src = mw.image.current_original;
                mw.top().trigger('imageSrcChanged', [mw.image.current, mw.image.current_original])
                mw.image.current_need_resize = true;
            }
        });


    });

</script>

