<?php only_admin_access(); ?>



<script src="<?php print modules_url()?>editor/html_editor/html_editor.js"></script>


<script>






    $(document).ready(function () {
        var fields = mw.html_editor.get_edit_fields(true);



        mw.html_editor.build_dropdown(fields);
        mw.html_editor.populate_editor();

        // mw.history.load
     //   mw.html_editor.init();

//
//        $(window.parent).on('saveEnd', function () {
//            alert( 213213 );
//
//        });






        //
    })


</script>
<style>
    #select_edit_field_wrap {
        width: 400px;
    }
</style>
<table>
  <tr>
    <td width="80%">
      <div id="select_edit_field_wrap"></div>

    </td>
    <td width="20%">
      <button onclick="mw.html_editor.reset_content();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Reset content'); ?></button>

    </td>
  </tr>
</table>


