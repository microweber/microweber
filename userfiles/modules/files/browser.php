<?php
only_admin_access();
/**
 * Simple file browser
 *
 * Gets all files from dir and output them in a template
 *
 * @package		modules
 * @subpackage	files
 * @category	modules
 */
 ?>
<?php
  // Explore the files via a web interface.
  $script = $config['url']; // the name of this script
  $path   =   MEDIAFILES; // the path the script should access
  $path_restirct   =   MW_USERFILES; // the path the script should access
 if(isset($params['path']) and trim($params['path']) != '' and trim($params['path']) != 'false'){

	  $path   =   $params['path']; // the path the script should access

 }

 $path = urldecode($path);


 $path = str_replace($path_restirct,'',$path);

 //$data = rglob($path);
  $params_\Files\Api::get = array();
   $params_\Files\Api::get['directory']  =  $path_restirct.$path;

   if (isset($params['search'])) {
		   $params_\Files\Api::get['search']  =  $params['search'];
	}

	  if (isset($params['sort_by'])) {
		   $params_\Files\Api::get['sort_by']  =  $params['sort_by'];
	}
	  if (isset($params['sort_order'])) {
		   $params_\Files\Api::get['sort_order']  =  $params['sort_order'];
	}

 if(isset($params_\Files\Api::get['directory']) and !is_dir($params_\Files\Api::get['directory'])){
  mw_warn('You are trying to open invalid folder');
 }  else if(isset($params_\Files\Api::get['directory']) and is_dir($params_\Files\Api::get['directory']) and !is_writable($params_\Files\Api::get['directory'])){
  mw_warn('Your folder is not writable. You wont be able to upload in it.');
 }
  //  $params['keyword']
 $data = \Files\Api::get($params_\Files\Api::get);

 $path_nav = explode(DS,$path);

?>
<script>

PreviousFolder = [];

</script>

<div class="mw-o-box mw-file-browser">
  <?php //if(in_array('breadcrumb', $_GET) and $_GET['breadcrumb'] == 'true'){ ?>
  <div class="mw-o-box-header"> <a href="javascript:;" onclick="mw.url.windowHashParam('path', PreviousFolder);" class="mw-ui-btn mw-ui-btn-small right" style="float: right;"><span class="backico"></span>
    <?php _e("Back"); ?>
    </a>  <span class="mw-browser-uploader-path">
    <?php if(isarr($path_nav )): ?>
    <?php

$path_nav_pop = false;
foreach($path_nav  as $item): ?>
    <?php

if($path_nav_pop  == false){
	$path_nav_pop = $item;
} else {

$path_nav_pop = $path_nav_pop.DS.$item;

}
 if(strlen($item)>0):
 ?>
    <script>PreviousFolder.push('<?php print urlencode($path_nav_pop) ?>');</script>
    <a href="#path=<?php print urlencode($path_nav_pop) ?>" style="color: #212121;"><span class="<?php print $config['module_class']; ?> path-item"><?php print ($item) ?></span></a>&raquo;
    <?php endif; endforeach ; ?>
    <?php endif; ?>
    </span> </div>
  <?php // } ?>
  <script>
    PreviousFolder.length > 1 ? PreviousFolder.pop() : '';
    PreviousFolder = PreviousFolder.length > 1 ? PreviousFolder[PreviousFolder.length-1] : PreviousFolder[0];
 </script>
  <div class="mw-o-box-content" id="mw-browser-list-holder">


    <?php if(isset($data['dirs'] )): ?>
    <ul class="mw-browser-list">
      <?php foreach($data['dirs']  as $item): ?>
      <?php  $dir_link = str_replace($path_restirct,'',$item); ?>
      <li>
        <a title="<?php print basename($item).'&#10;'.dirname($item); ?>" href="#path=<?php print urlencode($dir_link); ?>"><span class="ico icategory"></span><span><?php print basename($item); ?></span></a>
        <span class="mw-close" onclick="deleteItem('<?php print urlencode($dir_link); ?>', '<?php print basename($item); ?>');"></span>
      </li>
      <?php endforeach ; ?>
    </ul>
    <?php else: ?>
    <?php endif; ?>
    <div class="vSpace"></div>
    <div class="mw-o-box-hr"></div>
    <div class="vSpace"></div>
    <?php if(isset($data['files'] )): ?>
    <ul class="mw-browser-list">
      <?php foreach($data['files']  as $item): ?>
      <li>



      <a title="<?php print basename($item).'&#10;'.dirname($item); ?>" class="mw-browser-list-file mw-browser-list-<?php print substr(strrchr($item,'.'),1); ?>" href="<?php print dir2url($item) ?>"  onclick="mw.url.windowHashParam('select-file', '<?php print dir2url($item) ?>'); return false;">
        <?php $ext = strtolower(get_file_extension($item)); ?>

        <?php


   if($ext == 'jpg' or $ext == 'png'  or $ext == 'gif'  or $ext == 'jpeg'  or $ext == 'bmp'): ?>
        <img src="<?php print thumbnail(dir2url($item), 48, 48); ?>" />
        <?php else: ?>
        <span class="mw-fileico mw-fileico-<?php print $ext ; ?>"><?php print $ext ; ?></span>
        <?php endif; ?>
        <span><?php print basename($item) ?></span> </a>

        <?php $rand = uniqid(); ?>

         <div class="mw-file-item-check">
            <label class="mw-ui-check left">
                <input type="checkbox" onchange="gchecked()" name="fileitem" id="v<?php print $rand; ?>" value="<?php print $item;  ?>" /><span></span>
            </label>
            <span class="mw-close right" onclick="deleteItem(mwd.getElementById('v<?php print $rand; ?>').value);"></span>
         </div>
        </li>
      <?php endforeach ; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
