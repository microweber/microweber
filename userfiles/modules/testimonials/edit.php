<?php only_admin_access(); ?>
<script>
    $(document).ready(function () {
        $("#add-testimonial-form").submit(function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            var url = "<?php print api_url('save_testimonial'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module_parent("testimonials");
                mw.reload_module("testimonials/list");
                $("#add-testimonial-form").find("input[type=text], textarea").val("");
                window.TTABS.set(0)
            });
        });
    });
</script>
<?php $data = false; ?>
<?php if(isset($params['edit-id'])): ?>
<?php $data = get_testimonials("single=true&id=".$params['edit-id']); ?>
<?php endif; ?>
<?php 

if(!isset($data['id'])){
 $data['id']= 0; 
}
if(!isset($data['name'])){
 $data['name']= 'name'; 
}
if(!isset($data['content'])){
 $data['content']= ''; 
}
if(!isset($data['read_more_url'])){
 $data['read_more_url']= ''; 
}
 ?>

<form id="add-testimonial-form">
  <?php if(($data['id']) == 0): ?>
  <h3>Add new testimonial</h3>
  <?php else: ?>
  <h3>Edit testimonial</h3>
  <?php endif; ?>
  <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Name</label>
    <input type="text" name="name" value="<?php print $data['name'] ?>" class="mw-ui-field w100">
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Content</label>
    <textarea  name="content" class="mw-ui-field w100"><?php print $data['content'] ?></textarea>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">"Read more" link</label>
    <input type="text" name="read_more_url" value="<?php print $data['read_more_url'] ?>" class="mw-ui-field w100">
  </div>
  <hr>
  <input type="submit" name="submit" value="Save" class="mw-ui-btn pull-right"/>
</form>
