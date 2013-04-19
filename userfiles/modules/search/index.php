<? $seach_prefix = crc32( $params['id'] ); ?>
<script  type="text/javascript">
mw.require('events.js', true);
</script>
<script type="text/javascript">
    mw.require("url.js", true);
    mw.require("tools.js", true);
    mw.require("forms.js", true);
</script>
<script  type="text/javascript">
 $(document).ready(function(){
 
			mw.on.hashParam("keyword<? print $seach_prefix ?>", function(){

			 var dis = this;
			
			 if(dis!==''){
			
				 mw.$('#search_results_holder_<?  print $params['id'] ?>').attr('keyword', dis);
				 mw.$('#search_results_holder_<?  print $params['id'] ?>').attr('content_type', 'all');
				 mw.$('#search_results_holder_<?  print $params['id'] ?>').attr('limit', '5');
				 mw.$('#search_results_holder_<?  print $params['id'] ?>').attr('ajax_paging', 'true');


				 mw.$('#search_results_holder_<?  print $params['id'] ?>').show();
				 mw.load_module('posts', '#search_results_holder_<?  print $params['id'] ?>',function(){
         
  				});
				 
				
				
				
				
				
				
			} else {
				
				 mw.$('#search_results_holder_<?  print $params['id'] ?>').hide();
			}
			
			}); 	
				
		 
 }); 
 </script>

<div id="search_box_holder_<?  print $params['id'] ?>">
  <input type="text" class="input-large search-query" placeholder="<?php _e("Search"); ?>"   onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('keyword<? print $seach_prefix ?>',this.value)})">
  <div id="search_results_holder_<?  print $params['id'] ?>"> </div>
</div>
