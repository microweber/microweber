


<h2>
<? if($params['title'] != false): ?>
<? print $params['title']; ?>
<? else : ?>
<? print $config['params']['title']['default']; ?>
<? endif; ?>
</h2>
   
   <? //p($params); ?>
   
 
   
   <? category_tree($params) ?>
 