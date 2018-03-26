<div id="image_settings_modal_holder">
    <link rel="stylesheet" href="<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.css" type="text/css"/>
    <script src="<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.js"></script>

    <style scoped="scoped">

        #the-image-holder {
            position: relative;
            text-align: center;
            max-width: 100%;
            height: 300px;
        }

        #mwimagecurrentoverlay{
            position: absolute;
            display: block;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color:rgba(0, 0, 0, 0.2);
        }

        #the-image-holder img {
            max-width: 100%;
            max-height: 100%;
            box-shadow: 0 0 4px -2px #000;
            -webkit-box-shadow: 0 0 4px -1px #000;
        }

        #editmenu .mw-ui-btn {
            line-height: 15px;
        }

    </style>

    <div class='image_settings_modal'>


        <div id="the-image-holder"><!-- Image will be placed here --></div>
        <div class="mw-ui-box-content">
            <div style="text-align:center;padding-bottom: 12px;">
                <div id="cropmenu" class="mw-ui-btn-nav" style="display: none;">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info" onclick="DoCrop()"><?php _e("Crop"); ?></span>
                    <span class="mw-ui-btn mw-ui-btn-medium" onclick="cropcancel()"><?php _e("Cancel"); ?></span>
                </div>
            </div>
            <div class="mw-ui-field-holder" style="padding-bottom: 20px;" id="editmenu">
                <div class="mw-ui-btn-nav pull-left" style="margin-right:12px">


          <span class="mw-ui-btn mw-ui-btn-icon" onclick="mw.createCropTool();">
            <span class="mw-icon-crop"></span>
          </span>
                    <span class="mw-ui-btn mw-ui-btn-icon" onclick="mw.image.rotate(mw.image.current);mw.image.current_need_resize = true;mw.$('#mw_image_reset').removeClass('disabled')">
            <span class="mw-icon-app-refresh-empty"></span>
          </span>

                </div>


                <div class="mw-dropdown mw-dropdown-default pull-left" id="" style="width: 140px;">
                    <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val"><?php _e("Effects"); ?></span>
                    <div class="mw-dropdown-content" style="display: none;">
                        <ul>
                            <li value="vintage" onclick="mw.image.vintage(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><a href="javascript:;"><?php _e("Vintage Effect"); ?></a></li>
                            <li value="grayscale" onclick="mw.image.grayscale(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><a href="javascript:;"><?php _e("Grayscale"); ?></a></li>
                        </ul>
                    </div>
                </div>
                <span style="margin-left: 10px;" class='mw-ui-btn mw-ui-btn-invert mw-ui-btn-savetheimage pull-right'><?php _e("Update"); ?></span>


                <span style="margin-left: 10px;" class="mw-ui-btn mw-ui-btn-warn pull-right disabled" id="mw_image_reset"><?php _e("Reset"); ?></span>

                <span style="margin-left: 10px;" class='mw-ui-btn mw-ui-btn-change-image pull-right'><?php _e("Change"); ?></span>

            </div>


            <div class="mw-ui-field-holder" style="padding-bottom: 20px;display: none" id="overlayholder">
                <label class="mw-ui-label"><?php _e('Overlay color'); ?></label>
                <input type="text" class="mw-ui-field w100" id="overlaycolor" placeholder="Enter color"/>
                <script>
                  mw.require('css_parser.js', 'css_parser');
                  mw.require('color.js', 'color_js');
                </script>
                <script>


                  CurrSRC = function(b){
                    var curr = parent.mw.image.currentResizing[0];
                    if(curr.nodeName == 'IMG'){
                      if(!b){
                        return curr.src;
                      }
                      else{
                        curr.src = b
                      }
                    }
                    else{
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
                        top.mw.wysiwyg.bgQuotesFix(curr)
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
                        $(".mw-image-holder-overlay", theImage.parentNode).css('backgroundColor', final);
                      }

                      $("#mwimagecurrentoverlay").css('backgroundColor', final)
                  }
                  $(document).ready(function(){
                    if(parent.mw.image.currentResizing[0].nodeName != 'IMG'){
                      $(".imeditor-image-description,.imeditor-image-link").remove()
                    }

                   if (self !== parent && !!parent.mw.image.currentResizing) {
                        theImage = parent.mw.image.currentResizing[0];
                    }


                    if(isImageHolder()){
                      $("#overlayholder, #alphaholder").show();
                      currentOverlay = $('.mw-image-holder-overlay',  theImage.parentNode);
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

                    $(".mw-ui-btn-change-image").on('click', function(){
                      top.mw.wysiwyg.media('#editimage');
                      thismodal.remove()

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
                        <label class="mw-ui-label"><?php _e("Image Alternative Text"); ?> <span class="mw-icon-help-outline mwahi tip" data-tipposition="bottom-right"
                                                                                                data-tip="<?php _e("Text that appears if image fails to load. (Important for Search Engines)"); ?>"></span></label>
                        <textarea class="mw-ui-field w100" placeholder='<?php _e("Enter Description"); ?>' id="image-alt"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>


  isImageHolder = function(){
    return mw.tools.hasClass(parent.mw.image.currentResizing[0].parentNode, 'mw-image-holder')
  }


    mw.createCropTool = function () {
        mw.$('#cropmenu').show();
        mw.$('#editmenu').hide();
        cropImage = $('#mwimagecurrent');
        cropImage.cropper({
            dragCrop: false,
            autoCrop: true,
            done: function (data) {
              mw.$('.cropper-dragger', cropImage[0].parentNode).bind('dblclick', function () {
                  DoCrop();
              });
            }
        });
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

    }
    cropcancel = function () {

        mw.$(".cropper-container").remove();
        mw.$('#cropmenu').hide();
        mw.$('#editmenu').show();
        var newimg = new Image();
        newimg.src = cropImage.attr('src');
        newimg.id = 'mwimagecurrent';
        cropImage.replaceWith(newimg);
    }

    $(mwd).ready(function () {




        if (mw.tools.hasParentsWithTag(theImage, 'a')) {


            $("#link").val($(mw.tools.firstParentWithTag(theImage, 'a')).attr("href"));
        }


        mw.image.current_need_resize = false;

        var src = CurrSRC(),
            title = theImage.title,
            alt = theImage.alt;
        mw.$("#the-image-holder").html("<img id='mwimagecurrent' src='" + src + "' /><span id='mwimagecurrentoverlay'></span>");

         if(!!window.previewbg){
          $("#mwimagecurrentoverlay").css('backgroundColor', previewbg) 
         }


        mw.image.current_original = src;

        mw.image.current = mwd.querySelector("#mwimagecurrent");

        mw.$("#image-title").val(title);
        mw.$("#image-alt").val(alt);

        mw.$(".mw-ui-btn-savetheimage").click(function () {



            CurrSRC(mw.image.current.src)

            theImage.align = mw.image.current_align;
            theImage.title = mw.$("#image-title").val();
            theImage.alt = mw.$("#image-alt").val();

            if (mw.image.current_need_resize && theImage.nodeName == 'IMG') {
                mw.image.preload(mw.image.current.src, function (w, h) {
                    theImage.style.width = w + 'px';
                    theImage.style.height = 'auto';
                    // parent.mw.wysiwyg.normalizeBase64Image(theImage);
                    parent.mwd.getElementById('mw-image-settings-modal').modal.remove();
                });
            }

            parent.mw.wysiwyg.normalizeBase64Image(theImage);

            var link_url = $("#link").val();
            if (link_url != '') {
                if (mw.tools.hasParentsWithTag(theImage, 'a')) {
                    $(mw.tools.firstParentWithTag(theImage, 'a')).attr("href", link_url);
                }
                else {
                    $(theImage).wrap('<a href="' + link_url + '"></a>');
                }
            }

            setColor(true);
            parent.mw.wysiwyg.change(mw.tools.firstParentWithClass(theImage, 'edit'));

            window.top.$(window.top).trigger('imageSrcChanged', [theImage, CurrSRC()])

            parent.document.getElementById('mw-image-settings-modal').modal.remove();

        });

        mw.$("#mw_image_reset").click(function () {
            if (!$(this).hasClass("disabled")) {
                mw.image.current.src = mw.image.current_original;
                window.top.mw.trigger('imageSrcChanged', [mw.image.current, mw.image.current_original])
                mw.image.current_need_resize = true;
            }
        });


    });

</script>