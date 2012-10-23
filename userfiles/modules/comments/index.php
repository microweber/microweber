<?
$data = $params;
 if(!isset($params['to_table'])){
	
$data['to_table'] = 'table_content';
}

 

 if(!isset($params['to_table_id'])){
	
	 
	 if(defined('PAGE_ID') == true){
	 $data['to_table_id'] = PAGE_ID;
	 } 
	
	 if(defined('POST_ID') == true){
	 $data['to_table_id'] = POST_ID;
	 } 
	 
	 
}


?>
<script  type="text/javascript">
  mw.require("forms.js");
 $(document).ready(function(){
  mw.$('form#comments-form-<? print $data['id'] ?>').submit(function() {
     mw.form.post('form#comments-form-<? print $data['id'] ?>', '<? print site_url('api/post_comment'); ?>')
   return false;
  });
});
</script>

<div class="comments_form" id="comments-<? print $data['id'] ?>">
  <form id="comments-form-<? print $data['id'] ?>">
  
      <input type="text" name="to_table_id" value="<? print $data['to_table_id'] ?>">
   <input type="text" name="to_table" value="<? print $data['to_table'] ?>">
  
  
  
    Your name: <br>
    <input type="text" name="comment_name">
    <br>
    <br>
    Website: <br>
    <input type="text" name="comment_website">
    <br>
    <br>
    Your email: <br>
    <input type="text" name="comment_email">
    <br>
    <br>
    Your comments: <br>
    <textarea name="comment_body" rows="15" cols="50"></textarea>
    <br>
    <br>
    <input type="submit" value="Submit">
  </form>
</div>
