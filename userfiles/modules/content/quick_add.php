

 <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/mw.ui.css"/>



 <style type="text/css">


     #le_create_content_sidebar{
       width: 10%;
       min-width: 120px;
       background: #147DC1;
       color: white;
     }
     #le_create_content_sidebar a{
       color: white;
     }
     #le_create_content_container .mw-ui-col-container{
       padding: 35px;
     }

     .tqpy-l{
       display: block;
       margin: 20px auto
     }
     .acfle_layout{
       float: left;
       overflow: hidden;
       position: relative;
       margin-right: 20px;
       display: none;

     }
     .acfle_layouts_browser .acfle_layouts_browser_template:first-child .acfle_layout{
       display: block;
     }

     .acfle_layout h4{
       font-weight: 100;
       font-size: 14px;
       color: #595959;
       padding: 15px 0;
       white-space: nowrap;
       overflow: hidden;
       text-overflow: ellipsis
     }


     .acfle_layout_frame{
       overflow: hidden;
       position: relative;
       height: 420px;
       width: 355px;
       padding: 20px;
       background: white;
       border: 1px solid #EDEDED;
    box-shadow: 0 0 6px #F3F3F3;
     }
     .acfle_layouts_browser_template h2{
       color: #439DD8;
       font-weight: 100;
       font-size: 18px;
       padding: 10px 20px 10px 0;
       cursor: pointer;
       display: inline-block;
       background: url(<?php print INCLUDES_URL; ?>img/acfle_title_arr.png) no-repeat right center;
     }

     .acfle_layouts_browser_template{
       overflow: hidden;
       position: relative;
       clear: both;
       padding-bottom: 15px;
     }

@media (max-width: 768px) {
  .acfle-nav {
    float: left;
  }

  .acfle-nav li{
    float:left
  }
  .acfle-nav li a{
   padding: 20px 8px;
  }
  #le_create_content_sidebar{
    width: 100%;
  }

  .tqpy-l{
       float: left;
       margin:10px 20px;
     }

}

 </style>




<div class="mw-ui-row" id="le_create_content">
<div class="mw-ui-col" id="le_create_content_sidebar">

<div class="mw-ui-col-container">


<a href="javascript:;" class="tqpy-l"></a>



<ul class="acfle-nav">
  <li><a href="javascript:;">Pages</a></li>
  <li><a href="javascript:;">Posts</a></li>
  <li><a href="javascript:;">Product</a></li>
  <li><a href="javascript:;">Category</a></li>
  <li><a href="javascript:;">My Stuff</a></li>
  <li><a href="javascript:;">Templates</a></li>
</ul>


</div>
 </div>


<div class="mw-ui-col" id="le_create_content_container">
<div class="mw-ui-col-container">
<h1>Add page</h1>
<?php
$templates= mw('content')->site_templates();




/*
*/






?>
<?php if(!empty($templates)):  ?>


<div class="acfle_layouts_browser">
 

 <?php foreach($templates as $template): ?>
 <div class="acfle_layouts_browser_template">
 <h2><?php print $template['name'] ?></h2>



 
    <?php
	$layout_options = array();
	$layout_options ['site_template'] = $template['dir_name'];
	$layouts = mw('layouts')->get_all($layout_options);
		
	 if(!empty($layouts)): ?>
        <?php $i=0;
		

		 foreach($layouts as $item): ?>
        <?php $i++; ?>
 

<?php 

$safe_name =  str_replace('/','___',$item['layout_file']);
$safe_name =  str_replace('\\','___',$safe_name);
 
?>

<div class="acfle_layout">

    <div class="acfle_layout_frame"><iframe
            frameborder="0"
            src="javascript:;"
            data-src="<?php print site_url();?>non-existing-page-preview?quick-view=true&preview_template=<?php print $template['dir_name'] ?>&preview_layout=<?php print $safe_name ?>&content_id=0">
    </iframe></div>

    <h4><?php print $item['name'] ?></h4>
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

