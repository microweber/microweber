<?php exit('this is old file and will be removed'); ?><link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/ui.css"/>
<style >
#le_create_content_sidebar {
	width: 10%;
	min-width: 120px;
	background: #147DC1;
	color: white;
}
#le_create_content_sidebar a {
	color: white;
}
#le_create_content_container .mw-ui-col-container {
	padding: 35px;
}
.tqpy-l {
	display: block;
	margin: 20px auto
}
.acfle_layout {
	float: left;
	overflow: hidden;
	position: relative;
	margin-right: 20px;
	display: none;
}
.acfle_layouts_browser_template.active .acfle_layout {
	display: block;
}
.acfle_layout h4 {
	font-weight: 100;
	font-size: 14px;
	color: #595959;
	padding: 15px 0;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis
}
.acfle_layout_frame {
	overflow: hidden;
	position: relative;
	height: 420px;
	width: 355px;
	padding: 10px;
	background: white;
	border: 1px solid #EDEDED;
	box-shadow: 0 0 6px #F3F3F3;
}
.acfle_layouts_browser_template h2 {
	color: #439DD8;
	font-weight: 100;
	font-size: 18px;
	padding: 10px 20px 10px 0;
	cursor: pointer;
	display: inline-block;
 background: url(<?php print mw_includes_url();
?>img/acfle_title_arr.png) no-repeat right center;
}
.acfle_layouts_browser_template h2.active {
 background: url(<?php print mw_includes_url();
?>img/acfle_title_arr_active.png) no-repeat right center;
}
.acfle_layouts_browser_template {
	overflow: hidden;
	position: relative;
	clear: both;
	padding-bottom: 15px;
}
.acfle_layouts_browser iframe {
	height: 1700px;
	transform: scale(0.29, 0.3);
	transform-origin: 0 0 0;
	width: 1300px;
}
 @media (max-width: 768px) {
.acfle-nav {
	float: left;
}
.acfle-nav li {
	float:left
}
.acfle-nav li a {
	padding: 20px 8px;
}
#le_create_content_sidebar {
	width: 100%;
}
.tqpy-l {
	float: left;
	margin:10px 20px;
}
}
</style>
<script>


 continuousFrameLoading = function(arr){
    var l = arr.length, i = 0, curr = 0;
    if(l===0){ return false;}
    if(mw.tools.hasClass(arr[0], 'activated')) { return false; }
    for( ; i<l; i++){
        var el = $(arr[i]);
        el.addClass("activated");
        if(i == 0){
            el[0].src = el.dataset("src");
        }
        else{
            arr[curr].el = el;
            arr[curr].onload = function(){
               if(this.el.dataset("src") != ''){
                  this.el[0].src = this.el.dataset("src");
                  this.el.dataset("src", "");
               }
            }
            arr[curr].onerror = function(){
              if(this.el.dataset("src") != ''){
                this.el[0].src = this.el.dataset("src");
                this.el.dataset("src", "");
              }
            }
            curr++;
        }
    }
 }


 $(mwd).ready(function(){

    mw.$(".acfle_layouts_browser_template h2").on("click", function(){
      var h = $(this);
      if(h.hasClass("active")){
         h.removeClass("active");
         h.parent().removeClass("active");
      }
      else{
         mw.$(".acfle_layouts_browser_template h2, .acfle_layouts_browser_template").removeClass("active");
         h.addClass("active");
         h.parent().addClass("active");
         continuousFrameLoading(h[0].parentNode.querySelectorAll('iframe'));
         /*
         if($(h[0].parentNode.querySelector('iframe')).dataset("src") != ''){
            $(h[0].parentNode.querySelectorAll('iframe')).each(function(){
              $(this).attr("src", $(this).dataset("src"))
            })
         }  */

      }
    });


    continuousFrameLoading(document.querySelector('.acfle_layouts_browser_template').querySelectorAll('iframe'));

 });

 </script>
	<script>











	mw_add_content_with_template = function(template_dir,layout_name){

	mw.dialogIframe({
              url:mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=true&id=mw-quick-page",
              template:'mw_modal_simple',
              width:800,
              height:600,
              name:'quick_page',
              title:'New Page'
           });

	 }








						</script>
<div class="mw-ui-row" id="le_create_content">
	<div class="mw-ui-col" id="le_create_content_sidebar">
		<div class="mw-ui-col-container"> <a href="javascript:;" class="tqpy-l"></a>
			<ul class="acfle-nav">
				<li><a href="javascript:;"><?php _e('Pages'); ?></a></li>
				<li><a href="javascript:;"><?php _e('Posts'); ?></a></li>
				<li><a href="javascript:;"><?php _e('Product'); ?></a></li>
				<li><a href="javascript:;"><?php _e('Category'); ?></a></li>
				<li><a href="javascript:;"><?php _e('My Stuff'); ?></a></li>
				<li><a href="javascript:;"><?php _e('Templates'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="mw-ui-col" id="le_create_content_container">
		<div class="mw-ui-col-container">
			<h1><?php _e('Add page'); ?></h1>
			<?php
$templates= site_templates();




/*
*/






?>
			<?php if(!empty($templates)):  ?>
			<div class="acfle_layouts_browser">
				<?php $c = 0; ?>
				<?php $templates = array_reverse($templates); ?>
				<?php foreach($templates as $template): ?>
				<?php $c++; ?>
				<div class="acfle_layouts_browser_template<?php if($c==1){print " active";} ?>">
					<h2 class="<?php if($c==1){print "active";} ?>"><?php print $template['name'] ?></h2>

					<?php
	$layout_options = array();
	$layout_options ['site_template'] = $template['dir_name'];
	$layouts = mw()->layouts_manager->get_all($layout_options);

	 if(!empty($layouts)): ?>
					<?php $i=0;


		 foreach($layouts as $item): ?>
					<?php $i++; ?>
					<?php

$safe_name =  str_replace('/','___',$item['layout_file']);
$safe_name =  str_replace('\\','___',$safe_name);

?>
					<div class="acfle_layout">
						<div class="acfle_layout_frame" onclick="mw_add_content_with_template('<?php print $template['dir_name'] ?>', '<?php print $safe_name ?>' );">

							<!--<iframe
              frameborder="0"
              scrolling="no"

                src="javascript:;"
                data-src="<?php print site_url();?>non-existing-page-preview?quick-view=true&preview_template=<?php print $template['dir_name'] ?>&preview_layout=<?php print $safe_name ?>&content_id=0"


              >
      </iframe>-->
	  					<?php if(isset($item['screenshot']) and $item['screenshot'] != false):  ?>
						<img src="<?php print $item['screenshot'] ?>" />



						<?php endif; ?>
						</div>

						<h4><a href="javascript:;" onclick="mw_add_content_with_template('<?php print $template['dir_name'] ?>', '<?php print $safe_name ?>' );"><?php print $item['name'] ?></a></h4>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php endforeach;  ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
