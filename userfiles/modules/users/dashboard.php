<?   $dash = dashboard_items($params);   ?>
 <? $file = dirname(__FILE__).'/dashboard_item.php';
  $file = normalize_path( $file, false);
//  var_dump($dash);
 ?>

  <? if(!empty($dash)): ?>
  <ul class="user_activity">
    <? loop($dash['log'], $file) ?>
  </ul>
  <? else: ?>
  <h1>Dashboard is empty</h1>
  <? endif; ?>