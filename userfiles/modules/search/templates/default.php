<?php

/*

type: layout

name: Default

description: Default Search template

*/

  ?>

<div class="mw-search mw-search-default" id="search_box_holder_<?php  print $params['id'] ?>">

    <input type="text"
         id="search_field_<?php  print $params['id'] ?>"
         class="input-xlarge mw-search-field pull-left"
         placeholder="<?php _e("Search"); ?>"
    />
    <button class="btn  pull-left"
      onclick="mw.url.windowHashParam('keyword<?php print $seach_prefix ?>',mwd.getElementById('search_field_<?php  print $params['id'] ?>').value);">
      <i class="icon-search"></i>
    </button>




  <div class="mw-search-results" id="search_results_holder_<?php  print $params['id'] ?>"> </div>
</div>



<script>

 $(document).ready(function(){

			mw.on.hashParam("keyword<?php print $seach_prefix ?>", function(){
                mw.$("#search_box_holder_<?php  print $params['id'] ?>").addClass("loading");
    			var key = this;
    			if(key!==''){
                   var holder =  mw.$('#search_results_holder_<?php  print $params['id'] ?>');
                   mw.search(key, holder, {
                     done:function(){
                       mw.$("#search_box_holder_<?php  print $params['id'] ?>").removeClass("loading");
                     }
                   });
    			} else {
    				 mw.$('#search_results_holder_<?php  print $params['id'] ?>').hide();
    			}
			});

 });

</script>
