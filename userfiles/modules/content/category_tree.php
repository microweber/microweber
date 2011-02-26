


<h2>
<? if($params['title'] != false): ?>
<? print $params['title']; ?>
<? else : ?>
<? print $config['patams']['title']['default']; ?>
<? endif; ?>
</h2>
   
   
   
 
   
   <? category_tree($params) ?>
 