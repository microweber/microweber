<?php only_admin_access();?>

<h2>Cache confinguration</h2>
<label class="mw-ui-label">Cache storage type</label>
<?php   $enable_server_cache_storage = static_option_get('enable_server_cache_storage','server');

 if($enable_server_cache_storage == false){
	$enable_server_cache_storage = 'default';
 }
$memcache_enabled = extension_loaded('memcache');
 
  ?>
<div class="mw-ui-select">
  <select name="enable_server_cache_storage" class="mw_option_field"   type="text" option-group="server" option-type="static" data-refresh="<?php print $params['module'] ?>">
    <option value="default" <?php if($enable_server_cache_storage == 'default'): ?> selected="selected" <?php endif; ?>>Files Cache</option>
     <option value="apc" <?php if($enable_server_cache_storage == 'apc'): ?> selected="selected" <?php endif; ?>>APC Cache</option>
    <?php if($memcache_enabled): ?>
    <option value="memcache" <?php if($enable_server_cache_storage == 'memcache'): ?> selected="selected" <?php endif; ?>>Memcache Server</option>
    <?php endif ?>
  </select>
</div>
<div class="vSpace"></div>
<?php if($memcache_enabled and $enable_server_cache_storage == 'memcache'): ?>
<label class="mw-ui-label-inline">memcache servers</label>
<input name="memcache_servers" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="server"  data-refresh="<?php print $params['module'] ?>" option-type="static"  value="<?php print static_option_get('memcache_servers','server'); ?>" />
<br>
<small>You can set multiple servers by seperating them by comma. Ex: 127.0.0.1:11211, 192.168.0.1:11211</small>
<?php $memcache_servers = static_option_get('memcache_servers','server');
if($memcache_servers != false){
$memcache_servers = trim($memcache_servers);	
$memcache_servers  = explode(',',$memcache_servers );
}



?>
<?php if(isarr($memcache_servers )): ?>
<?php foreach($memcache_servers  as $item): ?>
<?php 
    
	$h = explode(':',$item);
  if(!isset($h[1])){
    $h[1] =11211; 
  } else {
    $h[1] = trim($h[1] ); 
  }
   $h[0] = trim($h[0] );
  $host = $h[0];
  $port = $h[1];
	$memcache = new \memcache();
$memcache->addServer($h[0], $h[1]);
$stats = @$memcache->getExtendedStats();
$available = (bool) $stats["$host:$port"];
if ($available && @$memcache->connect($host, $port)){
    // memcache is there
	$conn = 1;
}else{
	$conn = 0;
}
	
	
 
	 
	?>
<?php if( $conn != true): ?>
<div class="mw-notification mw-error">
  <div> <span>Cannot connect to <?php print $item ?></span> </div>
</div>
<?php endif; ?>
<?php
 

if( $conn == true){
	?>
<?php
	$version = $memcache->getVersion();
	 
    $tmp_object = new stdClass;
    $tmp_object->str_attr = rand();
    $tmp_object->int_attr = 123;
    
    $memcache->set('key', $tmp_object, false, 10) or die ("Failed to save data at the server");
	$get_result = $memcache->get('key');
	 ?>
<div class="mw-notification mw-success">
  <div> <span class="ico icheck"></span> <span>Connected to <?php print $item ?></span> </div>
</div>
<?php
}
  
    
    ?>
<?php endforeach ; ?>
<?php endif; ?>
<?php endif; ?>
