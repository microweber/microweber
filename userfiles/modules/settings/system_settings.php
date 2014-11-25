<?php only_admin_access();?>
<?php $here = dirname(__FILE__); ?>


   <?php if( $params['option_group'] == 'admin__import'){  ?>
      <h2><?php _e("Import"); ?></h2>
   <?php } ?>

<div class="mw-settings-list<?php if(isset($params['option_group'])): ?> mw-settings-list-<?php print strtolower(trim($params['option_group'])) ?><?php endif; ?>">
  <?php if(isset($params['option_group'])): ?>
  <?php $here_file = $here.DS.'group'.DS.trim($params['option_group']).'.php' ; ?>
  <?php if(is_file($here_file)): ?>



  <module="settings/group/<?php print $params['option_group']; ?>" />

   <?php  else: ?>
  <module="<?php print module_name_decode($params['option_group']); ?>" />
  <?php endif; ?>
  
      
       <?php  else: ?>
  <?php // _e("No options found"); ?>
  <?php endif; ?>
</div>
