<?php only_admin_access();?>
<? $here = dirname(__FILE__); ?>



<div class="mw-settings-list<? if(isset($params['option_group'])): ?> mw-settings-list-<? print strtolower(trim($params['option_group'])) ?><? endif; ?>">
  <? if(isset($params['option_group'])): ?>
  <? $here_file = $here.DS.'group'.DS.trim($params['option_group']).'.php' ; ?>
  <? if(is_file($here_file)): ?>
  <module="settings/group/<? print $params['option_group'] ?>" />
  
   <?  else: ?>
  <module="<? print module_name_decode($params['option_group']); ?>" />
  <? endif; ?>
  
      
       <?  else: ?>
  <? // _e("No options found"); ?>
  <? endif; ?>
</div>
