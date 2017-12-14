<div class="module-live-edit-settings">
    <span class="mw-ui-btn mw-ui-btn-invert pull-right" onclick="Bxslider.create();">Add new slide</span>

    <h2>bxSlider - Settings</h2>
    <module type="admin/modules/templates" simple="true"/>

    <div id="bxslider-settings">

      <script>mw.require('icon_selector.js')</script>
      <script>mw.require('ui.css')</script>
      <script>mw.require('wysiwyg.css')</script>



        <style>

        .item-icon ul {
          height: 220px;
          padding: 12px;
          background: white;
          box-shadow: 0 0 6p -3px rgba(0,0,0.5);
          min-width: 300px;
        }

        .item-icon li {
          margin: 5px 0;
          float: left;
          width: 33.333%;
          text-align: center;
          list-style: none;
          font-size: 33px;
          cursor: pointer;
          color: #777
        }

          .item-icon{
            position: relative;
            display: block;
            z-index: 10;
          }

            .bxslider-skinselector {
                width: 150px;
            }

            .bxslider-images-holder .bgimg {
                width: 120px;
                height: 120px;
                border: 1px solid #eee;
                box-shadow: 0 2px 2px -2px #000;
                background-size: contain;
            }

            .bxslider-setting-item {
                margin: 15px 0;
            }

            .bxslider-images-holder .mw-icon-close {
                position: absolute;
                top: 1px;
                right: 1px;
                cursor: pointer;
                z-index: 2;
                display: block;
                width: 20px;
                height: 20px;
                text-align: center;
                padding: 4px;
                background-color: white;
            }

            .bxslider-images-holder .mw-icon-close:hover {
                background-color: black;
                color: white;
            }

            .bxslider-images-holder {
                overflow: auto;
            }

            .bxslider-images-holder li {
                list-style: none;
                float: left;
                width: 120px;
                margin: 0 12px 12px 0;
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
                background: white;
                position: relative;
            }

            .bxslider-setting-item > .mw-ui-box-header {
                cursor: pointer;
            }

            .mw-icon-drag {
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
            }

            .mw-ui-box-header .mw-icon-close {
                margin-right: 12px;
                font-size: 14px !important;
            }

            .item-icon input{
              position: absolute;
              opacity: 0;
              left: 0;
              top: 0;
            }
            .item-icon .mw-ui-field-holder{
              padding: 0;
            }

        </style>

        <?php
        $defaults = array(
            'images' => '',
            'primaryText' => 'My bxSlider',
            'secondaryText' => 'Your slogan here',
            'seemoreText' => 'See more',
            'url' => '',
            'urlText' => '',
            'skin' => 'default'
        );

        $settings = get_option('settings', $params['id']);
        $json = json_decode($settings, true);

        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        $module_template = get_option('data-template', $params['id']);
        if(!$module_template){
            $module_template = 'default';
        }
        $module_template_clean = str_replace('.php','',$module_template);

        $default_skins_path = $config['path_to_module'] . 'templates/'.$module_template_clean.'/skins';
        $template_skins_path = template_dir() . 'modules/bxslider/templates/'.$module_template_clean.'/skins';
        $skins = array();

        if (is_dir($template_skins_path)) {
            $skins = scandir($template_skins_path);
        }

        if (empty($skins) and is_dir($default_skins_path)) {
            $skins = scandir($default_skins_path);
        }

        $skinSelector = '<select class="mw-ui-field bxslider-skinselector">';

        foreach ($skins as $skin) {
            $isphp = substr($skin, -4) == '.php';
            if ($isphp) {
                $val = substr($skin, 0, -4);
                $skinSelector .= '<option value="' . $val . '">' . $val . '</option>';
            }
        }

        $skinSelector .= '</select>';
        $count = 0;

        foreach ($json as $slide) {
            $count++;
            if (isset($slide['skin']) == false or $slide['skin'] == '') {
                $slide['skin'] = 'default';
            }
            ?>


            <div class="mw-ui-box  bxslider-setting-item" data-skin="<?php print $slide['skin']; ?>">
                <div class="mw-ui-box-header" onclick="mw.accordion(this.parentNode);">
                    <span class="mw-icon-drag pull-right show-on-hover"></span>
                    <span class="mw-icon-close show-on-hover pull-right" onclick="deletebxsliderslide(event);"></span>
                    <span class="mw-icon-gear"></span> Slide <?php print $count; ?>
                </div>

                <div class="mw-ui-box-content mw-accordion-content" style="display: none;">
                    <div class="mw-ui-row-nodrop">
                        <div class="mw-ui-col" style="width: 170px;">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Skin</label>
                                <?php print $skinSelector; ?>
                            </div>
                        </div>
                        <div class="mw-ui-col" style="width: 70px;">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Icon</label>
                                <span class="item-icon"></span>
                                <span class="item-icon-value" style="display: none"><?php print isset($slide['icon']) ? $slide['icon'] : ''; ?></span>
                            </div>
                        </div>

                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Main text</label>
                                <input type="text" class="mw-ui-field bxslider-main-text w100 " value="<?php print $slide['primaryText']; ?>">
                            </div>
                        </div>

                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Description</label>
                                <input type="text" class="mw-ui-field bxslider-secondary-text w100" value="<?php print $slide['secondaryText']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label">URL</label>
                        <input type="text" class="mw-ui-field bxslider-url" value="<?php print $slide['url']; ?>">
                    </div>

                    <?php if (!isset($slide['seemoreText'])) {
                        $slide['seemoreText'] = 'See more';
                    } ?>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label">See more text</label>
                        <input type="text" class="mw-ui-field bxslider-seemore" value="<?php print $slide['seemoreText']; ?>">
                    </div>


                    <div class="bxslider-images">
                        <ul class="bxslider-images-holder">
                            <?php
                            if (isset($slide['images'])) {
                                $arr = explode(',', $slide['images']);
                                if (sizeof($arr) > 0) {
                                    foreach ($arr as $image) {
                                        if ($image != '') {
                                            ?>
                                            <li>
                                                <span class="bgimg" data-image="<?php print $image; ?>" style="background-image:url(<?php print $image; ?>);"></span>
                                                <span class="mw-icon-close" onclick="deletebxsliderimage(event);"></span>
                                            </li>
                                        <?php }
                                    }
                                }
                            } ?>
                        </ul>

                        <span class="mw-ui-btn mw-ui-btn-invert bxslider-uploader"><span class="mw-icon-upload"></span>Add Image</span>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script>
         $(document).ready(function(){

           initIcons()

        })

        initIcons = function(){
          $(".item-icon").each(function(){
            var el = this;
            if(!el.__ready){
              el.__ready = true;
            mw.iconSelector.iconDropdown(this, {
              mode:'absolute',
              value:$(this).next('.item-icon-value').html(),
              onchange:function(val){
                $('input', el).val(val);

              }
            })
            }
          })
          setTimeout(function(){
            $(".item-icon input").addClass('bxslider-icon')
          }, 100)

        }
            deletebxsliderimage = function (e) {
                $(mw.tools.firstParentWithTag(e.target, 'li')).remove();
                Bxslider.save();
            };

            deletebxsliderslide = function (e) {
                if (confirm('Are you sure you want to delete this slide')) {
                    $(mw.tools.firstParentWithClass(e.target, 'bxslider-setting-item')).remove();
                    Bxslider.save();
                }
            };

            Bxslider = {
                collect: function () {
                    var data = {}, all = mwd.querySelectorAll('#bxslider-settings .bxslider-setting-item'), l = all.length, i = 0;

                    for (; i < l; i++) {
                        var item = all[i];
                        data[i] = {};
                        data[i]['primaryText'] = item.querySelector('.bxslider-main-text').value;
                        data[i]['secondaryText'] = item.querySelector('.bxslider-secondary-text').value;
                        data[i]['seemoreText'] = item.querySelector('.bxslider-seemore').value;
                        data[i]['url'] = item.querySelector('.bxslider-url').value;
                        data[i]['skin'] = item.querySelector('.bxslider-skinselector').value;
                        data[i]['icon'] = item.querySelector('.bxslider-icon').value;
                        data[i]['images'] = '';

                        if (item.querySelector('.bxslider-images-holder .bgimg') !== null) {
                            var imgs = item.querySelectorAll('.bxslider-images-holder .bgimg'), imgslen = imgs.length, ii = 0;

                            for (; ii < imgslen; ii++) {
                                if ((ii + 1) !== imgslen) {
                                    data[i]['images'] += $(imgs[ii]).dataset('image') + ',';
                                }

                                else {
                                    data[i]['images'] += $(imgs[ii]).dataset('image');
                                }
                            }
                        }
                    }

                    return data;
                },

                save: function () {
                    mw.$('#settingsfield').val(JSON.stringify(Bxslider.collect())).trigger('change');
                },

                initItem: function (item) {
                    $(item.querySelectorAll('input[type="text"]')).bind('keyup', function () {
                        mw.on.stopWriting(this, function () {
                            Bxslider.save();
                        });
                    });

                    var skin = $(item).dataset('skin');
                    $('.bxslider-skinselector', item).val(skin);
                    $(item.querySelectorAll('select')).bind('change', function () {
                        Bxslider.save();
                    });

                    var up = mw.uploader({
                        filetypes: 'images',
                        element: item.querySelector('.bxslider-uploader')
                    });

                    $(up).bind('FileUploaded', function (a, b) {
                        $(item.querySelector('.bxslider-images-holder')).append('<li><span class="bgimg" data-image="' + b.src + '" style="background-image:url(' + b.src + ');"></span><span class="mw-icon-close" onclick="deletebxsliderimage(event);"></span></li>');
                        Bxslider.save();
                    });

                    $(item.querySelector('.bxslider-images-holder')).sortable({
                        stop: function () {
                            Bxslider.save();
                        }
                    });
                },

                initSlideSettings: function () {
                    var all = mwd.querySelectorAll('#bxslider-settings .bxslider-setting-item'), l = all.length, i = 0;
                    for (; i < l; i++) {
                        if (!!all[i].prepared) continue;
                        var item = all[i];
                        item.prepared = true;
                        Bxslider.initItem(item);
                    }
                },

                create: function () {
                    var last = $('.bxslider-setting-item:last');
                    var html = last.html();
                    var item = mwd.createElement('div');
                    item.className = last.attr("class");
                    item.innerHTML = html;
                    $(item.querySelectorAll('input')).val('');
                    $(item.querySelectorAll('.bxslider-images-holder .bgimg')).remove();
                    $(item.querySelectorAll('.mw-uploader')).remove();
                    last.after(item);
                    Bxslider.initItem(item);
                     initIcons()
                }
            }

            $(window).bind('load', function () {
              if(thismodal.resize){
                thismodal.resize(800);
              }

                Bxslider.initSlideSettings();
                mw.$("#bxslider-settings").sortable({
                    items: "> .bxslider-setting-item",
                    handle: "> .mw-ui-box-header .mw-icon-drag",
                    axis: 'y',
                    stop: function () {
                        Bxslider.save();
                    }
                });
            });
        </script>
    </div>
</div>

<input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>