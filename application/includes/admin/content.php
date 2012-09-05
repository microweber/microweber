<module type="pages_menu" append_to_link="/editmode:y" link="<? print admin_url('view:content/edit_content_id:{id}'); ?>" />
<? $is_edit = url_param('edit_content_id'); ?>


<? if($is_edit == false): ?>
list?
<? else: ?>
 

 <module data-type="content/edit_page" data-content="<? print $is_edit ?>" />

<? endif; ?>