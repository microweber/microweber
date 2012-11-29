<script type="text/javascript">
    var murl = "<? print $config['url_to_module'] ?>";

    mw.require(murl+'style.css');
</script>
<script type="text/javascript">


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

$(document).ready(function(){
  mw.simpletabs(mwd.getElementById('comments_module'));
});

</script>




<div id="comments_module">


<div class="comments-tabs mw_simple_tabs mw_tabs_layout_stylish">
    <ul class="mw_simple_tabs_nav">
        <li><a href="javascript:;">Comments</a></li>
        <li><a href="javascript:;">Settings</a></li>
    </ul>
    <div class="tab" id="the_comments">

          <label class="mw-ui-label"><?php _e("Search"); ?>:</label>
          <input  class="mw-ui-field" type="search"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
          <module type="comments/search_content" id="mw_admin_posts_with_comments"    />


          <div class="<? print $config['module_class'] ?> mw_comments_admin_dashboard" id="mw_comments_admin_dashboard">
            <h2>New - green</h2>
            <module type="comments/manage"  is_moderated="n" />
            <h2>Old - white</h2>
            <module type="comments/manage"  is_moderated="y" />
          </div>
          <div class="<? print $config['module_class'] ?> mw_comments_admin_for_post" id="mw_admin_posts_with_comments_edit"> </div>


    </div>
    <div class="tab">
        Settings will be here ...
    </div>
</div>





</div>
