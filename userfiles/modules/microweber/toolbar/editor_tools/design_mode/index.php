<?php only_admin_access() ?>
<?php
$ref = site_url();
if(isset($_SERVER['HTTP_REFERER']))
{

    if(strstr($ref, $_SERVER['HTTP_REFERER'])){
        $ref = $_SERVER['HTTP_REFERER'];
    }

}




?>



Test
<iframe src="<?php print $ref ?> " width="100%" height="1000px;"></iframe>
