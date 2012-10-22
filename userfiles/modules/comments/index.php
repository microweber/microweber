<script  type="text/javascript">
  mw.require("forms.js");
 $(document).ready(function(){
  mw.$('form#comments-form-<? print $params['id'] ?>').submit(function() {
     mw.form.post('form#comments-form-<? print $params['id'] ?>', '<? print site_url('api/post_comment'); ?>')
   return false;
  });
});
</script>

<div class="comments_form" id="comments-<? print $params['id'] ?>">
  <form id="comments-form-<? print $params['id'] ?>">
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
