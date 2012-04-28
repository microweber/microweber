<?

/**
 * 
 * 
 * Generic module to add content form
 * @author Peter Ivanov
 * @package content



 
 
 

 @param $category | id of the category you want to add post in | default:false
 @param $title | the title of the form | default:'Add new content'
 


 */

?>
<? 
if($params['title'] == false){
	$params['title'] = 'Add new content'; 
}

 

?>
<? get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' ); ?>

<? add_post_form($params) ?>