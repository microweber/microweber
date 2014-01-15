<?php

/*

type: layout

name: Default

description: Default Search template

*/

  ?>

<div class="mw-search mw-search-default" id="search_box_holder_<?php  print $params['id'] ?>">

   <div class="row">
    <div class="col-xs-10">
        <input type="text"
               id="search_field_<?php  print $params['id'] ?>"
               class="form-control mw-search-field"
               placeholder="<?php _e("Search"); ?>" />
    </div>
    <div class="col-xs-2">
      <button class="btn btn-default pull-left"
        onclick="mw.url.windowHashParam('keyword<?php print $seach_prefix ?>', mwd.getElementById('search_field_<?php  print $params['id'] ?>').value);">
        <i class="icon-search glyphicon glyphicon-search"></i>
      </button>
    </div>
   </div>
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


            mw.$("#search_field_<?php  print $params['id'] ?>").bind("keyup", function(e){
               if(e.keyCode == 13){
                  mw.url.windowHashParam('keyword<?php print $seach_prefix ?>', this.value);
               }
            });

            var s = mw.url.windowHashParam("keyword<?php print $seach_prefix ?>");
            if(!!s){ mwd.getElementById('search_field_<?php  print $params['id'] ?>').value = decodeURIComponent(s); }

 });



</script>
