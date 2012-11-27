<?

 $rand = uniqid(); ?>
<script  type="text/javascript">



mw.require('forms.js');


$(document).ready(function(){
    window.addEventListener('message', receiveMessage, false);

    function receiveMessage(evt){
        alert("got message: "+evt.data);
    }

    mw.$('#modules_categories_tree_<? print $rand  ?> a').each(function(){
        var el = this;
        var id = el.attributes['data-category-id'].nodeValue;
        el.href = 'javascript:;';
        el.setAttribute('onclick', 'mw.url.windowHashParam("category",' + id + ');mw.$("#modules_admin_<? print $rand  ?>").attr("data-category",'+id+');');
    });


});


</script>
<script  type="text/javascript">
function mw_reload_all_modules(){

	mw.$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
	mw.$('#modules_admin_<? print $rand  ?>').attr('cleanup_db',1);
		 
  	 mw.load_module('admin/modules/manage','#modules_admin_<? print $rand  ?>');
	// mw.$('#modules_admin_<? print $rand  ?>').removeAttr('cleanup_db');

}


$(document).ready(function(){
  mw_show_modules_list();
});

function mw_show_modules_list(){

var ui = mw.url.getHashParams(window.location.hash).ui;

 if(ui  !== undefined){
    	mw.$('#modules_admin_<? print $rand  ?>').attr('data-show-ui',ui);
    } else {
    	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-show-ui');
    }

 
 
 var search = mw.url.getHashParams(window.location.hash).search;
  if(search  !== undefined){
    	mw.$('#modules_admin_<? print $rand  ?>').attr('data-search-keyword',search);
    } else {
    	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-search-keyword');
    }
	
	
	 var category = mw.url.getHashParams(window.location.hash).category;
	 if(category  !== undefined && parseInt(category)  !== 0){
  	mw.$('#modules_admin_<? print $rand  ?>').attr('data-category',category);
  } else {
  	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-category');
  }
  
  
	

// mw.reload_module('#modules_admin_<? print $rand  ?>');

mw.load_module('admin/modules/manage','#modules_admin_<? print $rand  ?>');


}



</script>

<div id="mw_index_modules">
  <div class="mw_edit_page_left" id="mw_edit_page_left"> <a href="#category=0" class="mw-ui-btn mw-ui-btn-small">Show all</a>
    <div class="mw-admin-side-nav" id="modules_categories_tree_<? print $rand  ?>" >
      <module type="categories" data-for="modules" id="modules_admin_categories_<? print $rand  ?>" />
    </div>
    <div class="tree-show-hide-nav"><a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Show uninstalled</a></div>
    <div class="tree-show-hide-nav">Add new modules</div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;width: 750px;">
    <div class="mw_edit_pages_nav" style="padding-left: 0;border: none">
      <div class="modules-index-bar">

        <span class="mw-ui-label-help font-11 left">Sort modules:</span>

        <input name="module_keyword" class="mw-ui-searchfield right" type="text" value="Search for modules"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />

        <div class="mw_clear"></div>



        <ul class="mw-ui-inline-selector">
          <li>
            <label class="mw-ui-check"><input name="module_show" class="mw_modules_filter_show" type="radio" value="admin" checked="checked" onchange="mw.url.windowHashParam('ui', this.value)" /><span></span><span>Admin modules</span></label>

          </li>
          <li>
            <label class="mw-ui-check"><input name="module_show"  class="mw_modules_filter_show"  type="radio" value="live_edit" onchange="mw.url.windowHashParam('ui', this.value)" /><span></span><span>Live edit modules</span></label>

          </li>
          <li>
            <label class="mw-ui-check"><input name="module_show"  class="mw_modules_filter_show"  type="radio" value="advanced"  onchange="mw.url.windowHashParam('ui', this.value)" /><span></span><span>Advanced</span></label>

          </li>
        </ul>






      </div>
      <div class="vSpace"></div>
    </div>
    <div id="pages_edit_container" > </div>
    <div id="modules_admin_<? print $rand  ?>" ></div>
  </div>
</div>
<button onclick="mw_reload_all_modules()">Reload modules</button>
<script type="text/javascript">

_modulesholder = mw.$('#modules_admin_<? print $rand  ?>');

mw.on.hashParam("category", function(){
	//
	 mw_show_modules_list();
 
 // mw.reload_module('#modules_admin_<? print $rand  ?>');
});

mw.on.hashParam("search", function(){
    mw_show_modules_list();
   // mw.reload_module('#modules_admin_<? print $rand  ?>');

});

mw.on.hashParam("ui", function(){
    mw_show_modules_list();
   
	//mw.reload_module('#modules_admin_<? print $rand  ?>');
});
</script>