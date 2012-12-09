<?php include (ADMIN_VIEWS_PATH . 'header.php'); ?>
<? if(is_admin() == false): ?>
<module type="users/login" />
<? else: ?>
<? $v1 = url_param('load_module'); ?>
<? $v = url_param('view');
 
  if($v1 != false){
	 $holder_cls = url_title($v1);
  }  else if($v != false){
	 $holder_cls = url_title($v);
  } else {
	  $holder_cls = false;
  }
  ?>

<div class="admin-main-wrapper <? print  $holder_cls ?>">
  <? if($v1 != false): ?>
  <?php
        
        $v_mod = module_name_decode($v1);
        
         if(is_module($v_mod)){
			  include_once (ADMIN_VIEWS_PATH . 'module_nav.php'); 
             $mod = load_module($v_mod, $attrs=array('view' => 'admin','backend' => 'true'));
             print $mod ;
         } else {
            print "No module found {$v_mod}" ;
             
         }
        
         ?>
  <? else : ?>
  <?php include_once (ADMIN_VIEWS_PATH . 'header_nav.php'); ?>
  <?php 
        
        
        
        $vf = ADMIN_VIEWS_PATH . $v. '.php';
        $vf = str_replace('..', '', $vf);
        if(is_file($vf)){
        //d($vf);	
        
        include ($vf);
        
        }
         
         else { ?>
  <?php
        
        $v_mod = module_name_decode($v);
      
         if($v_mod != '' and is_module($v_mod)){
             $mod = load_module($v_mod, $attrs=array('view' => 'admin','backend' => 'true'));
            
			
			 print $mod ;
         } else {
             
             include (ADMIN_VIEWS_PATH . 'index.php');
         }
        
         } ?>
  <? endif; ?>
</div>
<? endif; ?>
<?php	include (ADMIN_VIEWS_PATH . 'footer.php'); ?>
