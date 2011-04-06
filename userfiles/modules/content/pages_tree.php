<?php


$par =  CI::model('content')->getParentPagesIdsForPageIdAndCache($page['id']);

p($par);

$last =  end($par); // last

if($last == 0){
$from = 	$page['id'];
} else {
	$from = 	$last;
}
	
	
	
 CI::model('content')->content_helpers_getPagesAsUlTree($from , "<a href='{link}'   {removed_ids_code}  {active_code}  value='{id}' >{content_title}</a>", array($form_values['content_parent']), 'class="active"', array($form_values['id']) , 'class="hidden"' );

 ?>