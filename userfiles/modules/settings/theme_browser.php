  <!-- START THEME BROWSER TAB -->
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><hr /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />

      <script>

      $(document).ready(function(){

          mw.on.scrollBarOnBottom(window, 50, function(){
            var el = this;
            el.pauseScrollCallback();
            $.post("", function(){
                var a = '<li><a title="Nominate"href="javascript:;"><img contenteditable="false"alt="Nominate screenshot."src="http://prothemedesign.com/wp-content/uploads/2010/07/nominate-wordpress-theme-screenshot.jpg"><span class="mw-overlay"></span><span class="mw-ui-theme-list-description"><span class="mw-theme-browser-list-title">Nominate</span><span class="mw_clear"></span><span style="margin-right: 10px;"class="mw-ui-btn mw-ui-btn-medium">Get Started</span><span class="mw-ui-btn mw-ui-btn-medium">Live Demo</span></span></a></li><li><a title="Byline"href="javascript:;"><img contenteditable="false"alt="Byline screenshot."src="http://prothemedesign.com/wp-content/uploads/2011/06/byline-wordpress-theme-screenshot.jpg"><span class="mw-overlay"></span><span class="mw-ui-theme-list-description"><span class="mw-theme-browser-list-title">Byline</span><span class="mw_clear"></span><span style="margin-right: 10px;"class="mw-ui-btn mw-ui-btn-medium">Get Started</span><span class="mw-ui-btn mw-ui-btn-medium"><?php _e("Live Demo"); ?></span></span></a></li>';
                $("#mw-theme-browser-list").append(a);
                $("#mw-theme-browser-list").append(a);
                el.continueScrollCallback();
            });
          });


      });

      </script>

      <?php

        $themes = array(
            'Nominate' => 'http://prothemedesign.com/wp-content/uploads/2010/07/nominate-wordpress-theme-screenshot.jpg',
            'Byline' => 'http://prothemedesign.com/wp-content/uploads/2011/06/byline-wordpress-theme-screenshot.jpg',
            'uDesign' => 'http://www.themesmafia.org/wp-content/uploads/2012/09/u-design-theme-screenshot-400.jpg',
            'Impact'=>'http://1.s3.envato.com/files/44039565/Screenshots/01_main_screenshot.__large_preview.png'
        );

       ?>

      <div class="mw-theme-browser">
        <ul id="mw-theme-browser-list">
            <?php foreach($themes as $title=>$url): ?>
            <li>
                <a href="javascript:;" title="<?php print $title; ?>">
                    <img src="<?php print $url; ?>" alt="<?php print $title; ?> <?php _e("screenshot"); ?>." />
                    <span class="mw-overlay"></span>
                    <span class="mw-ui-theme-list-description">
                      <span class="mw-theme-browser-list-title"><?php print $title; ?></span>
                      <span class="mw_clear"></span>
                      <span class="mw-ui-btn mw-ui-btn-medium" style="margin-right: 10px;"><?php _e("Get Started"); ?></span>
                      <span class="mw-ui-btn mw-ui-btn-medium"><?php _e("Live Demo"); ?></span>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <!-- END THEME BROWSER TAB -->