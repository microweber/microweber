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

mw.bind_update_form_submit = function(){



	 mw.$('.mw-select-updates-list').submit(function() {

        Alert("aaaaaaaaaaaa");


        return false;

         if(mw.$(".update-items input:checked").length === 0){
           Alert("Please select at least one item to update.")
           return false;
         }

         if(!mw.$("#installsubmit").hasClass("disabled")){


               mw.tools.disable(mwd.getElementById('installsubmit'), 'Installing...', true);

               mw.form.post(mw.$('.mw-select-updates-list') , '<? print api_url(); ?>mw_apply_updates', function(){

                 mw.tools.enable(mwd.getElementById('installsubmit'));
                 //mw.notification.success("All updates are successfully installed.")
                 Alert("Updates are successfully installed.")
				 
				 $('#number_of_updates').fadeOut();
				 mw.reload_module('#mw-updates', function(){
					mw.bind_update_btns(); 
				 });

              });
         }

         return false;

    });	
	
}
mw.bind_update_btns = function() {


  mw.$('.mw-check-updates-btn').click(function() {
      if(!$(this).hasClass("disabled")){

          var el = this;

          mw.tools.disable(el, 'Checking...', true);

      	  $("#mw-updates").attr('force', 'true');

          $(mwd.body).addClass("loading")

      	  mw.reload_module("#mw-updates", function(a,b){
             $(mwd.body).removeClass("loading");
             mw.tools.enable(el);
			 mw.bind_update_form_submit();
             mw.notification.warning("<b>" + this.querySelectorAll("tr.update-items").length + " new updates.</b>");
      	  });


      }



	  });	
	
}
$(document).ready(function(){

	mw.bind_update_btns();
   	mw.bind_update_form_submit();

   



 
   
});

</script>


<style type="text/css">

#mw-updates-holder{
  padding: 20px;
  max-width: 960px;
}

#mw-update-table{

}

#updates-list-info{
  width:60%;
  width: calc(100% - 185px);
  width: -webkit-calc(100% - 185px);
}


</style>
<? $notif_count = mw_updates_count() ?>

<div id="mw-updates-holder">

<div class="mw-sided">
    <div class="mw-side-left" style="width: 150px;">
        <h2 class="mw-side-main-title"><span class="ico iupdate_big"></span><span>Updates</span><? if($notif_count !=0) : ?>&nbsp;<sup class="mw-notif-bubble" id="number_of_updates"><? print $notif_count  ?></sup><? endif; ?></h2>
        <span class="mw-check-updates-btn mw-ui-btn mw-ui-btn-medium" title="Current version <? print MW_VERSION ?>">Check for updates</span>
    </div>
    <div class="mw-side-left" id="updates-list-info" style="font-size: 12px;">
        <span style="font-size: 18px;"><?php print user_name(); ?></span>, we are constantly trying to improve Microweber. <br>
        Our team and many people around the world are working hard every day to provide you with stable system and new updates.
        Please excuse us if you find some mistakes and <a href="javascript:;" class="mw-ui-link">write us a message</a> for the things you need to see in MW or in some <a href="javascript:;" class="mw-ui-link">Module</a>.
    </div>
</div>

<div class="vSpace"></div>
<div class="vSpace"></div>



<module type="updates/list" id="mw-updates" />


</div>




