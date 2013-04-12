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
	  
	  $("#mw-updates").attr('force', 'true');
	  
	  mw.reload_module("#mw-updates");
	  
	  });
   
	 mw.$('.mw-select-updates-list').submit(function() { 

 
 mw.form.post(mw.$('.mw-select-updates-list') , '<? print $config["module_view"] ?>/apply_updates', function(){
	 
var obj =  (this);




	 if(mw.is.defined(obj) && obj != null){
	 mw.$('#update_log_<? print $params['id']; ?>').empty();
	 $.each(obj, function(index, value) { 
	 
 
	 
	 
mw.$('#update_log_<? print $params['id']; ?>').append(value);
});
	  }
 
	 });







 return false;
 
 
 });
   
 


 
   
});
</script>
<input type="button" value="Check for update" class="mw-check-updates-btn mw-ui-btn" />



<module type="updates/list" id="mw-updates" />
 


<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<img src="<? print $config['url_to_module'];?>update.jpg" />