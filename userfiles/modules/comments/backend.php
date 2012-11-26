<table   border="1">
  <tr>
    <td><?
	$comments_data = array();
$comments_data['to_table'] =  'table_content';
 $comments_data['debug'] =  'table_content';
 
 
 //$comments_data['count'] =  '1';
 // $comments_data['group'] =  'to_table_id';
$comments = get_comments($comments_data);
 
 //$comments = array_change_key($comments, 'comment_name', 'title');
 
	 ?></td>
    <td><h2>New - green</h2>
      <module type="comments/manage"  is_moderated="n" />
      <h2>Old - white</h2>
      <module type="comments/manage"  is_moderated="y" /></td>
  </tr>
</table>
