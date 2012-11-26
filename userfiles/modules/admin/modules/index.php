<?

 $rand = uniqid(); ?>
<script  type="text/javascript">



mw.require('forms.js');


mw.on.hashParam("category", function(){
  if(this  !== ''){
  	mw.$('#modules_admin_<? print $rand  ?>').attr('data-category',this);
  } else {
  	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-category');
  }
  mw.reload_module('#modules_admin_<? print $rand  ?>');
});

mw.on.hashParam("search", function(){
    if(this  !== ''){
    	mw.$('#modules_admin_<? print $rand  ?>').attr('data-search-keyword',this);
    } else {
    	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-search-keyword');
    }
    mw.load_module($module_name, '#modules_admin_<? print $rand  ?>', false, attributes);

});

mw.on.hashParam("ui", function(){
    mw_show_modules_list();
    if(this  !== ''){
    	mw.$('#modules_admin_<? print $rand  ?>').attr('data-show-ui',this);
    } else {
    	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-show-ui');
    }
});







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
	mw.$('#modules_admin_<? print $rand  ?>').attr('data-show-ui',this);
} else {
	mw.$('#modules_admin_<? print $rand  ?>').removeAttr('data-show-ui');
}


// mw.reload_module('#modules_admin_<? print $rand  ?>');

mw.load_module('admin/modules/manage','#modules_admin_<? print $rand  ?>');

	
}



</script>


<div id="mw_edit_pages_content">
  <div class="mw_edit_page_left" id="mw_edit_page_left">
    <div class="mw-admin-side-nav" id="modules_categories_tree_<? print $rand  ?>" >
      <module type="categories" data-for="modules" id="modules_admin_categories_<? print $rand  ?>" />
    </div>
    <div class="tree-show-hide-nav"><a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Show uninstalled</a></div>
    <div class="tree-show-hide-nav">Add new modules</div>
  </div>
  <div class="mw_edit_page_right">
    <div class="mw_edit_pages_nav" style="padding-left: 0;">
      <div class="top_label">Show modules: <br />
        Admin modules:
        <input name="module_show" class="mw_modules_filter_show" type="radio" value="admin" checked="checked" onchange="mw.url.windowHashParam('ui', this.value)" />
        <br />
        Live edit modules:
        <input name="module_show"  class="mw_modules_filter_show"  type="radio" value="live_edit" onchange="mw.url.windowHashParam('ui', this.value)" />
        <br />
        Advanced:
        <input name="module_show"  class="mw_modules_filter_show"  type="radio" value="advanced"  onchange="mw.url.windowHashParam('ui', this.value)" />
        Search:
        <input name="module_keyword" class="mw-ui-field mw_modules_filter_keyword" type="text"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      </div>
      <div class="vSpace"></div>
    </div>
    <div id="pages_edit_container" > </div>
    <div id="modules_admin_<? print $rand  ?>" ></div>
  </div>
</div>
<button onclick="mw_reload_all_modules()">Reload modules</button>
