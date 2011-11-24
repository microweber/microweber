
 </div><!-- /#content -->
              </div><!-- /#wrapper -->
            </div><!-- /#shadow-right -->
          </div><!-- /#shadow -->
        </div><!-- /#container -->
        <div id="footer-shadow">
          <div id="footer-shadow-right">
             <div id="footer">
                 <address>&copy; 2003-2010 <a href="http://yomexbg.com">www.yomexbg.com</a> Всички права са запазени.</address>
                 <div id="footer-right">
                    <a href="#" id="facebook">Стани почитател</a>
                    <a href="<?php print  site_url('main/rss'); ?>" id="rss">RSS</a>
                    <a href="#" id="sendmail">Сподели с приятел</a>
                    <a href="http://ooyes.net" id="web-design-company" title="Website design">OOYES.NET</a>
                    <a href="http://ooyes.net" id="griph" title="Website design">OOYES.NET</a>

                 </div>
                 <?php //print (memory_get_usage() / 1024 / 1024);
				 /*$m =  ($this->core_model->cache_storage_mem);
				 arsort($m);
				 $m = count($m);*/
				 ?>
                 <?php // global $mw_cache_storage;
				// p($mw_cache_storage);
				 
				  //p( $this->core_model->cache_storage_hits) ?>
                 
                   <?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>
             </div><!-- /#footer -->
          </div><!-- /#footer-shadow-right -->
        </div><!-- /#footer-shadow -->
        <div class="hidden">

        </div>

        <div class="hidden">
            <form id="recommend" action="#" method="post">
                <div class="heading"><h1>Препоръчай на приятел</h1></div>
                <label>Вашето име: *</label>
                <div class="contact_input">
                    <input type="text" id="r_name" class="required" />
                </div>
                <label>Вашият Email: *</label>
                <div class="contact_input">
                    <input type="text" id="r_sender_email" class="required-email" />
                </div>
                <label>Email на получателя: *</label>
                <div class="contact_input">
                    <input type="text" id="r_receiver_email" class="required-email" />
                </div>
                <label>Съобщение: </label>
                <div class="contact_textarea">
                    <textarea id="r_message" cols="" rows=""></textarea>
                </div>
                <input type="submit" class="search" value="Изпрати" />
                <div style="height: 10px;">&nbsp;</div>
           </form>
       </div>


     


	</body>
</html>