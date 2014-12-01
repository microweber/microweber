<div id="settings-holder">
            <?php

              $template_settings = get_option('shopmagdata', 'mw-template-shopmag');
              $template_settings = json_decode($template_settings, true);
              if(!isset($template_settings['color'])){
                $template_settings['color'] = '';
              }
              if(!isset($template_settings['layout'])){
                $template_settings['layout'] = '';
              }
              if(!isset($template_settings['fontfamily'])){
                $template_settings['fontfamily'] = '';
              }

            ?>
           <script>
            $(document).ready(function(){
               mw.$("#selectlayout [data-val='<?php print $template_settings['layout']; ?>']").addClass('active');
               mw.$(".template-color[data-val='<?php print $template_settings['color']; ?>']").addClass('active');
               mw.$("#smfontselect").val('<?php print $template_settings['fontfamily']; ?>');
            });
           </script>

            <div class="mw-ui-box-content" style="background-color: white">
                <label class="mw-ui-label">Layout type</label>
                <div class="mw-ui-btn-nav" id="selectlayout">
                    <a class="mw-ui-btn" onclick="smlayouttype('', this)" data-val=''>Fluid</a>
                    <a class="mw-ui-btn" onclick="smlayouttype('fixed', this)" data-val='fixed'>Fixed</a>
                </div>
                <hr>
                <label class="mw-ui-label">Colors</label>
               <script>
                   smsetcolor = function(el){
                    if(!$(el).hasClass('active')){
                        mw.tools.classNamespaceDelete(parent.mwd.body, 'shopmag-');
                        mw.tools.addClass(parent.mwd.body, $(el).dataset('val'));
                        mw.$(".template-color.active").removeClass("active")
                        mw.$(el).addClass("active");
                        SaveSettings();
                    }

                   }
                   smlayouttype = function(type, el){
                    if(type==''){
                      $(parent.document.body).removeClass('fixed');
                      $(el).addClass('active');
                      $(el).next().removeClass('active');
                    }
                    else if(type=='fixed'){
                       $(parent.document.body).addClass('fixed');
                       $(el).addClass('active');
                       $(el).prev().removeClass('active')
                    }
                    SaveSettings();
                   }
                   smfontselect = function(name){
                        mw.tools.classNamespaceDelete(parent.mwd.body, 'smfont-');
                        mw.tools.addClass(parent.mwd.body, name);
                        SaveSettings();
                   }
                   SaveSettings = function(){
                       var datafields =  {};

                       datafields.color = mw.$(".template-color.active").dataset('val');
                       datafields.layout = mw.$("#selectlayout .active").dataset('val');
                       datafields.fontfamily = mw.$("#smfontselect").val();

                      var data = {
                          option_key:'shopmagdata',
                          option_value:JSON.stringify(datafields),
                          option_group:'mw-template-shopmag'
                      }

                      $.post("<?php print api_url('save_option'); ?>", data, function(){
                        mw.notification.success('Changes saved');
                      });


                   }
               </script>
                <style>

                .template-color{
                  display: inline-block;
                  width: 20px;
                  height: 20px;
                  cursor: pointer;
                  margin:5px;
                  opacity: 0.3;
                  border: 2px solid transparent;
                }

                .template-color:hover,
                .template-color:focus{
                  opacity: 1;
                }

                .template-color.active{
                    background-color: #c27ac1;
                    border: 2px solid rgba(0, 0, 0, 0.50);
                    opacity: 1;
                    -webkit-transform: scale(1.2);
                    -ms-transform: scale(1.2);
                    -o-transform: scale(1.2);
                    -moz-transform: scale(1.2);
                    transform: scale(1.2);
                }

                </style>

                <span class="template-color" data-val="" onclick="smsetcolor(this)" style="background-color:#000;"></span>
                <span class="template-color" data-val="shopmag-red" onclick="smsetcolor(this)" style="background-color:#DB0743;"></span>
                <span class="template-color" data-val="shopmag-purple" onclick="smsetcolor(this)" style="background-color:#C27AC1;"></span> <br>
                <span class="template-color" data-val="shopmag-orange" onclick="smsetcolor(this)" style="background-color:#EE9A00;"></span>
                <span class="template-color" data-val="shopmag-blue" onclick="smsetcolor(this)" style="background-color:#0092DB;"></span>
                <span class="template-color" data-val="shopmag-violet" onclick="smsetcolor(this)" style="background-color:#CD6889;"></span>
                <hr>
                <label class="mw-ui-label">Font</label>
                <select class="mw-ui-field" id="smfontselect" onchange="smfontselect(this.value);">
                    <option value="">Source Sans Pro (Default)</option>
                    <option value="smfont-arial">Arial</option>
                    <option value="smfont-georgia">Georgia</option>
                    <option value="smfont-exo">Exo 2</option>
                    <option value="smfont-tahoma">Tahoma</option>
                </select>

            </div>


</div>   <!-- /#settings-holder -->