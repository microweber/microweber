<?   $dash = dashboard_items($params);   ?>
 <? $file = dirname(__FILE__).'/dashboard_item.php';
// var_dump($dir);
 ?>

  <? if(!empty($dash)): ?>
  <ul class="user_activity">
    <? loop($dash['log'], $file) ?>
  </ul>
  <? else: ?>
  <h1>Dashboard is empty</h1>
  <? endif; ?>