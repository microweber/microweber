<div id="image_settings_modal_holder">


    <script>
        mw.require("<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.css");
        mw.require("<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/cropper.min.js");
        mw.require("<?php print(mw_includes_url()); ?>toolbar/editor_tools/imageeditor/jquery-cropper.min.js");
        mw.require('css_parser.js');
        mw.lib.require('colorpicker');
        mw.require("files.js");
        mw.require("widgets.css");
    </script>

    <style>

        #the-image-holder {
            position: relative;
            text-align: center;
            max-width: 100%;
            height: 200px;
            direction: ltr !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            background-image:
                linear-gradient(45deg, #ccc 25%, transparent 25%),
                linear-gradient(-45deg, #ccc 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #ccc 75%),
                linear-gradient(-45deg, transparent 75%, #ccc 75%);
            background-size:20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
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
            max-height: 100%;
            box-shadow: 0 0 4px -2px #000;
            -webkit-box-shadow: 0 0 4px -1px #000;
        }


        @media (max-width:550px){

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
        var rotator;
        var _isrotating = false
        var imageRotate = function (img_object, angle) {
            if (!rotator) {
                rotator = mwd.createElement('canvas');
                rotator.style.top = '-9999px';
                rotator.style.left = '-9999px';
                rotator.style.position = 'absolute';
                rotatorContext = rotator.getContext('2d');
                document.body.appendChild(rotator);
            }
            if (!_isrotating) {
                _isrotating = true;
                img_object = img_object || mwd.querySelector("img.element-current");
                if (img_object === null) {
                    return false;
                }
                mw.image.preload(img_object.src, function () {
                    if (!img_object.src.contains("base64")) {
                        var currDomain = mw.url.getDomain(window.location.href);
                        var srcDomain = mw.url.getDomain(img_object.src);
                        if (currDomain !== srcDomain) {
                            mw.tools.alert("This action is allowed for images on the same domain.");
                            return false;
                        }
                    }
                    var angle = angle || 90;
                    var image = mw.$(this);
                    var w = this.naturalWidth;
                    var h = this.naturalHeight;
                    var contextWidth = w;
                    var contextHeight = h;
                    var x = 0;
                    var y = 0;
                    switch (angle) {
                        case 90:
                            contextWidth = h;
                            contextHeight = w;
                            y = -h;
                            break;
                        case 180:
                            x = -w;
                            y = -h;
                            break;
                        case 270:
                            contextWidth = h;
                            contextHeight = w;
                            x = -w;
                            break;
                        default:
                            contextWidth = h;
                            contextHeight = w;
                            y = -h;
                    }
                    rotator.setAttribute('width', contextWidth);
                    rotator.setAttribute('height', contextHeight);
                    rotatorContext.rotate(angle * Math.PI / 180);
                    rotatorContext.drawImage(img_object, x, y);
                    var data = rotator.toDataURL("image/png");
                    img_object.src = data;
                    _isrotating = false;
                    if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(img_object);
                });
            }
        }

        var grayscale = function (node) {
            var node = node || mwd.querySelector("img.element-current");
            if (node === null) {
                return false;
            }
            mw.image.preload(node.src, function () {
                var canvas = mwd.createElement('canvas');
                var ctx = canvas.getContext('2d');
                canvas.width = this.naturalWidth;
                canvas.height = this.naturalHeight;
                ctx.drawImage(node, 0, 0);
                var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
                for (var y = 0; y < imgPixels.height; y++) {
                    for (var x = 0; x < imgPixels.width; x++) {
                        var i = (y * 4) * imgPixels.width + x * 4; //Why is this multiplied by 4?
                        var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
                        imgPixels.data[i] = avg;
                        imgPixels.data[i + 1] = avg;
                        imgPixels.data[i + 2] = avg;
                    }
                }
                ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
                node.src = canvas.toDataURL();
                if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
            })
        },
        vr = [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
        vg = [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
        vb = [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199],
        vintage = function (node) {
            node = node || mwd.querySelector("img.element-current");
            if (node === null) {
                return false;
            }
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            mw.image.preload(node.src, function (w, h) {
                canvas.width = w;
                canvas.height = h;
                ctx.drawImage(node, 0, 0);
                var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height), l = imageData.data.length, i = 0;
                for (; i < l; i += 4) {
                    imageData.data[i] = vr[imageData.data[i]];
                    imageData.data[i + 1] = vg[imageData.data[i + 1]];
                    imageData.data[i + 2] = vb[imageData.data[i + 2]];
                    if (noise > 0) {
                        var noise = Math.round(noise - Math.random() * noise), j = 0;
                        for (; j < 3; j++) {
                            var iPN = noise + imageData.data[i + j];
                            imageData.data[i + j] = (iPN > 255) ? 255 : iPN;
                        }
                    }
                }
                ctx.putImageData(imageData, 0, 0);
                node.src = canvas.toDataURL();
                if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
                mw.$(canvas).remove()
            });
        },

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


                  <span class="mw-ui-btn tip" data-tip="<?php _e('Crop') ?>" onclick="createCropTool();">
                    <span class="mdi mdi-crop"></span>
                  </span>
                  <span class="mw-ui-btn mw-ui-btn-icon tip"
                        data-tip="<?php _e('Rotate'); ?>"
                        onclick="imageRotate(mw.image.current);mw.image.current_need_resize = true;mw.$('#mw_image_reset').removeClass('disabled')">
                    <span class="mdi mdi-refresh"></span>
                  </span>

                </div>


                <div class="mw-dropdown mw-dropdown-default pull-left">
                    <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val"><?php _e("Effects"); ?></span>
                    <div class="mw-dropdown-content" style="display: none;">
                        <ul>
                            <li value="vintage" onclick="vintage(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><?php _e("Vintage Effect"); ?></a></li>
                            <li value="grayscale" onclick="grayscale(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"><?php _e("Grayscale"); ?></li>
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

                    var isBG = false;


                  var CurrSRC = function(b){
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

                  var setColor = function(save){
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
                        if(url !== $('#mwimagecurrent')[0].src){
                            $('#mwimagecurrent')[0].src = url;
                        }
                      });


                  if (mw.parent().image.currentResizing) {
                      SelectedImage =  mw.parent().image.currentResizing[0];
                  }
                  else if (mw.parent().image.currentResizing) {
                      SelectedImage = mw.parent().element('.element-current').get(0);
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
                    mw.colorPicker({
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
            <div class="mw-ui-form-controllers-footer nav-actions">



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
        mw.$('.nav-actions').hide();
        cropImage = $('#mwimagecurrent');
        cropImage.cropper({
            crop: function (data) {
              mw.$('.cropper-dragger', cropImage[0].parentNode).on('dblclick', function () {
                  DoCrop();
              });
            }
        });
        $('.mw-ui-btn-nav.nav-actions').hide();
        $('#edititems').hide()
    }


  var DoCrop = function () {
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
        mw.$('.nav-actions').show();

        $('.mw-ui-btn-nav.nav-actions').show();
        $('#edititems').show()

    }
  var cropcancel = function () {

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

