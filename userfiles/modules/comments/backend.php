<table   border="1">
  <tr>
    <td><script type="text/javascript">

 
mw.on.hashParam("search", function(){
    if(this  !== ''){
    	$('#mw_admin_posts_with_comments').attr('data-search-keyword',this);
    } else {
    	$('#mw_admin_posts_with_comments').removeAttr('data-search-keyword');
    }
	 
    mw.reload_module('#mw_admin_posts_with_comments');

});


mw.on.hashParam("comments_for_content", function(){
    if(this  !== '' && this  != '0'){
		$('#mw_comments_admin_dashboard').hide();
		$('#mw_admin_posts_with_comments_edit').show();
    	$('#mw_admin_posts_with_comments_edit').attr('data-content-id',this);
		 mw.load_module('comments/manage', '#mw_admin_posts_with_comments_edit');
    } else {
    	$('#mw_admin_posts_with_comments_edit').removeAttr('data-content-id');
		$('#mw_comments_admin_dashboard').show();
		$('#mw_admin_posts_with_comments_edit').hide();

    }
	
	    

	
  //  mw.reload_module('#mw_admin_posts_with_comments');

});
 
</script> 
      Search:
      <input  class="mw-ui-field" type="search"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <module type="comments/search_content" id="mw_admin_posts_with_comments"    /></td>
    <td><div class="<? print $config['module_class'] ?> mw_comments_admin_dashboard" id="mw_comments_admin_dashboard">
        <h2>New - green</h2>
        <module type="comments/manage"  is_moderated="n" />
        <h2>Old - white</h2>
        <module type="comments/manage"  is_moderated="y" />
      </div>
      <div class="<? print $config['module_class'] ?> mw_comments_admin_for_post" id="mw_admin_posts_with_comments_edit"> </div></td>
  </tr>
</table>
