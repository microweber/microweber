<?php

/*

  type: layout

  name: Menu 

  description: List the client sites from WHMCS



*/

  ?>
  <style>


</style>
 <script>



 </script>

<?php if(function_exists('whm_get_user_products')): ?>
<?php
$client_producsts = whm_get_user_products();
$client_invoices = whm_get_user_invoices();
$whmuser_info = whm_get_user_info();

 ?>
<?php if(is_array($client_producsts)): ?>


<ul>
		<?php foreach(($client_producsts) as $item): ?>
		<?php $isActive = ( isset($item['status']) and strtolower($item['status']) == 'active' ) == true;  ?>
		<?php $isTerminated = ( isset($item['status']) and strtolower($item['status']) == 'terminated' ) == true;  ?>
		<?php $isCancelled = ( isset($item['status']) and strtolower($item['status']) == 'cancelled' ) == true;  ?>
		<?php if(!$isTerminated and !$isCancelled): ?>
		<?php if(is_array($item) and isset($item['pid']) and isset($item['domain'])): ?>
		<?php $status = strtolower($item['status']); ?>
		<?php
                        if($status == 'active'){
                            $cls = 'label-success';
            		    } elseif($status == 'pending'){
                            $cls = 'label-warning';
            			} elseif($status == 'cancelled'){
                            $cls = 'label-default';
            			} else{
            			    $cls = 'label-info';
            		    }
     ?>
		<?php if($isActive): ?>
		<li class="product-basic-info ">


                <a class="blue" href="http://<?php  print($item['domain']); ?>/" onclick="this.nextElementSibling.submit();return false;">
				    <?php  print($item['domain']); ?>
                    <small title="You are using <?php print($item['name']); ?>."><?php print($item['name']); ?></small>
				</a>


			<form class="pull-left" action="http://<?php print($item['domain']); ?>/api/user_login" target="_blank" method="post">
				<input type="hidden" name="username" value="<?php   print($item['username']); ?>" />
				<input type="hidden" name="password" value="<?php   print($item['password']); ?>" />
			   <?php /*	<input type="submit" name="Edit website" value="Edit website" class="xbtn xbtn-blue" />       */ ?>
				<input type="hidden" name="redirect" value="http://<?php print($item['domain']); ?>/?editmode=y" />
			</form>
			<?php $goto = "upgrade.php?type=package&id=".$item['id'] ;
				 //." with id ".$item['id'].""

$goto = api_url('panel_user_link').'?goto='.urlencode($goto);


       ?>
		</li>
		<?php endif; ?>
		<?php endif; ?>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php endif; ?>
