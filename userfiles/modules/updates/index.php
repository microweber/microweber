<?  only_admin_access();
 api_expose('updates');
if(url_param('add_module')){

}  

	$install = url_param('add_module');
	
	




?>
 
<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">

$(document).ready(function(){

	  mw.$('.mw-check-updates-btn').click(function() {
      if(!$(this).hasClass("disabled")){

            var el = $(this);
            el.addClass("disabled").html("Checking...");


      	  $("#mw-updates").attr('force', 'true');

          $(mwd.body).addClass("loading")

      	  mw.reload_module("#mw-updates", function(a,b){
                 $(mwd.body).removeClass("loading");
                 el.removeClass("disabled").html("Check for updates");


                 mw.notification.warning("<b>" + this.querySelectorAll("tr:not(.mw-table-head)").length + " new updates.</b>");
      	  });


      }



	  });
   
	 mw.$('.mw-select-updates-list').submit(function() {

 
 mw.form.post(mw.$('.mw-select-updates-list') , '<? print api_url(); ?>mw_apply_updates', function(){

     var obj =  (this);
	 if(mw.is.defined(obj) && obj != null){
	    mw.$('#mw-upd-log').val(obj);
	 }
 
});







 return false;
 
 
 });
   
 


 
   
});
</script>
<span type="button" class="mw-check-updates-btn mw-ui-btn">Check for updates</span>

<textarea id="mw-upd-log" style="display: none;"></textarea>

<module type="updates/list" id="mw-updates" />
 



<img src="<? print $config['url_to_module'];?>update.jpg" />