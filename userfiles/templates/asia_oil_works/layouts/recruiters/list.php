 <? 

$user_filter = array();
//$user_filter['is_public'] = 'y' ;
//$user_filter['role'] = 'company' ;


$users = CI::model ( 'users' )->getUsers($user_filter, $limit = false, $count_only = false); 


//p($users);






$us = array();
$temp = array();
?>
<? 
	$i = 0;
	foreach($users as $user){
	$temp[] = $user;  
	if($i%4==1) {
		
		$us[] = $temp;  
		$temp = array();
	}
	
	
	  $i++; }
	  
	  ?>
<? 
	  
	//  p($us);
	  ?>
      
      
      <div class="pad2 ishr"> <a href="#" class="print">Print</a>
      <h2>Companies</h2>
    </div>
    <div id="companies1">
      <? foreach($users as $user): ?>
      <div class="company_entry">
        <?  $cfs = get_custom_fields_for_user($user['id']); ?>
        <a href="<? print site_url('recruiters');    ?>/id:<? print $user['id'] ?>">
        <? if(!$cfs["picture"]["custom_field_value"]) {
		  
		 $cfs["picture"]["custom_field_value"] =   TEMPLATE_URL.'no_logo.gif';
	  }?>
        <span style="background-image: url('<? print site_url('phpthumb/phpThumb.php') ?>?src=<? print $cfs["picture"]["custom_field_value"]  ?>&h=200&w=200')"></span> <b><? print user_name($user['id']) ?></b> </a> </div>
      <? endforeach; ?>
    </div>
    <table cellpadding="0" cellspacing="0" id="companies">
      <tr>
        <td><a href="#"> <span style="background-image: url(<? print TEMPLATE_URL ?>img/t1.jpg)"></span> <b>Company Name</b> </a></td>
        <td><a href="#"> <span style="background-image: url(<? print TEMPLATE_URL ?>img/t2.jpg)"></span> <b>Company Name</b> </a></td>
        <td><a href="#"> <span style="background-image: url(<? print TEMPLATE_URL ?>img/t1.jpg)"></span> <b>Company Name</b> </a></td>
        <td><a href="#"> <span style="background-image: url(<? print TEMPLATE_URL ?>img/t2.jpg)"></span> <b>Company Name</b> </a></td>
        <td><a href="#"> <span style="background-image: url(<? print TEMPLATE_URL ?>img/t1.jpg)"></span> <b>Company Name</b> </a></td>
      </tr>
    </table>
    <br />
    <br />