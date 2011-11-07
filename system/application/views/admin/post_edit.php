<? $id = url_param('id'); ?>

<div class="box radius mainbox">
  <div class="box_header radius_t">
    <input name="save" class="sbm right" onclick="$('#save_post_form').submit()" type="button" value="Save post" />
    <input name="save" class="btn_light right"  onclick="quick_live_edit('<? print $id; ?>')"  type="button" value="Preview" />
    <h2>Editing post</h2>
  </div>
  <div class="box_content">
    <mw module="admin/posts/edit" id="<? print intval($id) ?>" >
  </div>
  <div class="box_footer radius_b">
    <input name="save" class="sbm right" onclick="$('#save_post_form').submit()" type="button" value="Save post" />
    <h2>Editing post</h2>
  </div>
</div>
