<?php

/*

  type: layout

  name: Client products

  description: List the client products from WHMCS



*/

  ?>
<?php if(isset($_REQUEST['update_product']) and $_REQUEST['update_product'] != 0): ?>
<module type="whmcs" template="update_product" product="<?php print intval($_REQUEST['update_product']) ?>" />
<?php elseif(isset($_REQUEST['payments']) ): ?>
<?php else: ?>
<module type="whmcs" template="invoices_bar" />


<script>



var max_time = 30;
var cinterval;
 
function countdown_timer(){
	 
  // decrease timer
  max_time--;
  document.getElementById('countdown_site').innerHTML = max_time;
  if(max_time == 0){
    clearInterval(cinterval);
  }
}
// 1,000 means 1 second.



refresh_this_module = function(){
	
	 if(window.refresh_this_module_counter_init === undefined){
		 window.refresh_this_module_counter_init = 1;
		 
	 } else {
		 
	 }
	 
	
	setTimeout(function(){
		
		mw.reload_module('#<?php print $params['id'] ?>');
		
		},30000);

	
}

</script>
 
 <script>
 $( document ).ready(function() {
	 
	  if(window.refresh_this_module_counter_init === undefined){
		 if(document.getElementById('countdown_site') !== null){
			 cinterval = setInterval('countdown_timer()', 1000);
		 }
	  }
	 
    
  });

 
 
 </script>
<?php 
$goto = "cart.php";
$goto = api_url('panel_user_link').'?goto='.urlencode($goto);
?>
<?php

$debug=false;
		if ($_SERVER['REMOTE_ADDR'] == '78.90.67.20') {
$debug=1;
		}

 ?>

<a class="btn btn-link right"  href="<?php   print($goto); ?>">Add new website</a>




<?php
      $client_producsts = whm_get_user_products();
      $client_invoices = whm_get_user_invoices();
      $whmuser_info = whm_get_user_info();
?>
<?php if(is_array($client_producsts)): ?>
<table class="user-sites-table user-sites-table-small">
  <thead>
    <tr>
      
        <th>Websites name</th>
        <th>Status</th>
     <!--   <th>Plan type</th>-->
    <!--    <th>Upgrade</th>-->
        <th>Full details</th>
    </tr>
  </thead>
<tbody>
<?php $only_one = false; ?>
<?php foreach(($client_producsts) as $item): ?>
<?php $isActive = ( isset($item['status']) and strtolower($item['status']) == 'active' ) == true;  ?>
<?php $isTerminated = ( isset($item['status']) and strtolower($item['status']) == 'terminated' ) == true;  ?>
<?php $isCancelled = ( isset($item['status']) and strtolower($item['status']) == 'cancelled' ) == true;  ?>
<?php if(!$isTerminated and !$isCancelled): ?>

<tr >
	<?php if(is_array($item) and isset($item['pid']) and isset($item['domain'])): ?>
    <?php $status = strtolower($item['status']); ?>
    <?php
        if($status == 'active'){
              $cls = 's-status-success';
        } elseif($status == 'pending'){
              $cls = 's-status-warning';
        } elseif($status == 'cancelled'){
              $cls = 's-status-cancelled';
        } else{
              $cls = 's-status-info';
        }
    ?>
 
	<td> 
		<?php if($isActive): ?>
 			<form class="pull-left" action="https://<?php print($item['domain']); ?>/api/user_login" target="_blank" method="post" id="site-login-form-<?php   print($item['id']); ?>">
				<input type="hidden" name="username" value="<?php   print($item['username']); ?>" />
				<input type="hidden" name="password" value="<?php   print($item['password']); ?>" />
                <a class="blue domainNAme" href="javascript:;" onclick="$(this.parentNode).submit()"><?php print($item['domain']); ?></a>
 
 <input type="hidden" name="OLD_redirect" value="http://<?php print($item['domain']); ?>/?editmode=y" />
                    				<input type="hidden" name="redirect" value="http://<?php print($item['domain']); ?>/admin/view:content" />
                                                         
			
            <div class="c"></div><br />
            <a class="btn btn-primary btn-sm " href="javascript:;" onclick="$(this.parentNode).submit()">Edit this site</a>
           <!-- <small style="font-size:10px;color:#ccc;">Site #<?php print $item['id']; ?> / <span title="You are using <?php print($item['name']); ?>."><?php print($item['name']); ?></span></small>-->
</form>
			<?php
                $goto = "upgrade.php?type=package&id=".$item['id'] ;
                $goto = api_url('panel_user_link').'?goto='.urlencode($goto);
            ?>

		<?php endif; ?>
		<?php if(!$isActive): ?>
            <span class="domainNAme"><?php print($item['domain']); ?></span>
		<?php endif; ?>
	</td>


    <td>
       <span class="label <?php print $cls; ?>" title="Your site is <?php print($item['status']); ?>.">
                <?php  print($item['status']); ?>
            </span>
            
           
 
 <?php
 
  if($status == 'pending'): ?>
 <?php if(($only_one == false and isset($item['regdate']) and $item['regdate'] == date("Y-m-d")) ): ?>
  <?php $only_one = 1; ?>
 <a href="javascript:mw.reload_module('#<?php print $params['id'] ?>')" title="Click here to refresh the status">Installing website...<b id="countdown_site"></b></a>

 <!--<br />
 <small style="color:red">Please wait few seconds</small>-->
 
 <script>
 $( document ).ready(function() {
   refresh_this_module();
  });

 
 
 </script>
 <?php else: ?> 
  <a href="javascript:mw.reload_module('#<?php print $params['id'] ?>')" title="Click here to refresh the status"><?php  print($item['status']); ?></a>

<?php endif; ?>


 <?php else: ?>
             <?php  print($item['status']); ?>

 <?php endif; ?>


    </td>
   

   <!-- <td>
 <td><span title="You are using <?php print($item['name']); ?>."><?php print($item['name']); ?></span></td>
    <a href="<?php print $goto ?>" class="user-sites-table-btn">Upgrade</a> </td>-->


	<td <?php if(!$isActive){ print " colspan='2'"; } ?>>
		<?php
            $goto = "clientarea.php?action=cancel&id=".$item['id'];
            $goto = $whmuser_info['dologin'].'&goto='.urlencode($goto);
       ?>

		<?php endif; ?>
		<?php if(!$isActive): ?>
		<?php
		    $prod_inv = whm_get_product_invoices($item['id']);
		 ?>

				<?php if(!empty($prod_inv)): ?>


				<a
                    href="<?php print $goto ?>"
                    class="user-sites-table-btn"
                    title="<?php $topay = 0; foreach(($prod_inv) as $inv):$topay+=$inv['amount']; ?><?php  print $inv['description']; ?> - $<?php  print $inv['amount']; ?>&#xA;<?php endforeach; ?>">
					Pay invoice of <span class="user_topay">$<strong> <?php print $topay; ?></strong></span></a>

                 	<?php
                       $goto = "viewinvoice.php?id=".$inv['invoiceid'];
                       $goto = $whmuser_info['dologin'].'&goto='.urlencode($goto);
                     ?>



				<?php else: ?>

				<?php $goto = "submitticket.php?step=2&deptid=1&relatedservice=S".$item['id']."&subject=Please activate my website ".$item['domain'] ;
                      $goto = $whmuser_info['dologin'].'&goto='.urlencode($goto);
                ?>
				<a href="<?php print $goto ?>" class="blue">Contact support</a>
				<?php endif; ?>

		<?php endif; ?>

             <?php if($isActive): ?>

		<?php /*<a class="blue" href="https://<?php   print($item['domain']); ?>/cpanel" target="_blank">See more</a>*/ ?>
        
        
        <?php
                $goto = "clientarea.php?action=productdetails&id=".$item['id'] ;
                $goto = api_url('panel_user_link').'?goto='.urlencode($goto);
            ?>
		<a class="blue" href="<?php print $goto ?>" target="_blank">See more</a>

         <?php endif; ?>


	</td>

</tr>

<?php endif; ?>

<?php endforeach; ?>


</tbody>
</table>
<script>

    mw.require("wysiwyg.js");




</script>
<?php endif; ?>
<?php endif; ?>
