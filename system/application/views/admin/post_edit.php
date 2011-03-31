<? $id = url_param('id'); ?>



      <div class="box radius mainbox">
        <div class="box_header radius_t">


        <input name="save" class="sbm right" onclick="$('#save_post_form').submit()" type="submit" value="Save changes" />
            <h2>Create new page</h2>



        </div>
        <div class="box_content">

<mw module="admin/posts/edit" id="<? print intval($id) ?>" >

        </div>
      </div>