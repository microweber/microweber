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

          var el = this;

          mw.tools.disable(el, 'Checking...', true);

      	  $("#mw-updates").attr('force', 'true');

          $(mwd.body).addClass("loading")

      	  mw.reload_module("#mw-updates", function(a,b){
             $(mwd.body).removeClass("loading");
             mw.tools.enable(el);
             mw.notification.warning("<b>" + this.querySelectorAll("tr.update-items").length + " new updates.</b>");
      	  });


      }



	  });
   
	 mw.$('.mw-select-updates-list').submit(function() {


         if(!mw.$("#installsubmit").hasClass("disabled")){


               mw.tools.disable(mwd.getElementById('installsubmit'), 'Installing...', true);

               mw.form.post(mw.$('.mw-select-updates-list') , '<? print api_url(); ?>mw_apply_updates', function(){

                var obj =  (this);
              	 if(mw.is.defined(obj) && obj != null){
              	    mw.$('#mw-upd-log').val(obj);
              	 }

                 mw.tools.enable(mwd.getElementById('installsubmit'));
                 mw.notification.success("All updates are successfully installed.")

              });
         }

         return false;

    });
   



 
   
});
</script>



<div class="mw-sided">
    <div class="mw-side-left" style="width: 150px;">
        <h2 class="mw-side-main-title"><span class="ico iupdate_big"></span><span>Updates</span></h2>
        <span class="mw-check-updates-btn mw-ui-btn mw-ui-btn-medium">Check for updates</span>
    </div>
    <div class="mw-side-left" style="width: 550px;font-size: 12px;">
        <strong><?php print user_name(); ?></strong>, we are constantly trying to improve Microweber. <br>
        Our team and many people around the world are working hard every day to provide you with stable system and new updates.
        Please excuse us if you find some mistakes and <a href="javascript:;" class="mw-ui-link">write us a message</a> for the things you need to see in MW or in some <a href="javascript:;" class="mw-ui-link">Module</a>.
    </div>
</div>

<div class="vSpace"></div>
<div class="vSpace"></div>

<textarea id="mw-upd-log" style="display: none;"></textarea>

<module type="updates/list" id="mw-updates" />




