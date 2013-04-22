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
            mw.url.windowDeleteHashParam("keyword<? print $seach_prefix ?>");
			mw.on.hashParam("keyword<? print $seach_prefix ?>", function(){

             mw.$("#search_box_holder_<?  print $params['id'] ?>").addClass("loading");

			 var dis = this;
			
			 if(dis!==''){

                 var holder =  mw.$('#search_results_holder_<?  print $params['id'] ?>');
                 holder
                      .attr('keyword', dis)
                      .attr('content_type', 'all')
                      .attr('limit', '5')
                      .attr('ajax_paging', 'true')
                      .attr('template', 'search')

                      .show();


				 mw.load_module('posts', '#search_results_holder_<?  print $params['id'] ?>',function(){
                    mw.$("#search_box_holder_<?  print $params['id'] ?>").removeClass("loading");
  				});

			} else {

				 mw.$('#search_results_holder_<?  print $params['id'] ?>').hide();
			}

			}); 	
        mw.$("#search_field_<?  print $params['id'] ?>").bind("keyup paste", function(e){
          mw.$("#search_box_holder_<?  print $params['id'] ?>").addClass("loading");
          if(e.type == 'paste'){
              mw.url.windowHashParam('keyword<? print $seach_prefix; ?>', this.value);

          }
          else{
            if(e.keyCode ==38){ //up
                var lis = '';
            }
            else if(e.keyCode == 40){ //down

            }





          }
        });





 });
 </script>

<div class="mw-search" id="search_box_holder_<?  print $params['id'] ?>">

  <div class="input-append">


  <input type="text"
         id="search_field_<?  print $params['id'] ?>"
         class="input-large mw-search-field"
         placeholder="<?php _e("Search"); ?>"
         onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('keyword<? print $seach_prefix ?>',this.value)})" />

<span class="add-on"><i class="icon-search"></i></span>
                </div>


  <div class="mw-search-results" id="search_results_holder_<?  print $params['id'] ?>"> </div>
</div>
