  <script type="text/javascript">
 $(document).ready(function() {

$("#original_link").change( function() {
  original_link_toggle_link_properties();
});

original_link_toggle_link_properties();

});
 
 
 
 
function original_link_toggle_link_properties(){
$the_val = $("#original_link").val();
	if($the_val != ''){
	$("#original_link_no_follow_row").fadeIn();
			$("#original_link_include_in_advanced_search_row").fadeIn();
			$("#originallinkhiddenModalContent_controller").fadeIn();
	}
}
</script>
Original link:

<input name="original_link"  id="original_link" type="text" value="<?php print $form_values['original_link']; ?>" />
          <?php if($form_values['is_from_rss'] == 'y' ) : ?>
          <img src="<?php print_the_static_files_url() ; ?>/icons/feed__arrow.png" alt=" " border="0">
          <?php endif; ?>
          <a href="#TB_inline?height=150&width=400&inlineId=originallinkhiddenModalContent&modal=false" id="originallinkhiddenModalContent_controller" style="display:none"   class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/link_edit.png" alt=" " border="0"></a>
          <div id="originallinkhiddenModalContent" style="display:none">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>Original link no_follow:</td>
                <td><select name="original_link_no_follow">
                    <option  <?php if($form_values['original_link_no_follow'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($form_values['original_link_no_follow'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select></td>
              </tr>
              <tr>
                <td>Original link include in advanced search:</td>
                <td><select name="original_link_include_in_advanced_search">
                    <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($form_values['original_link_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select></td>
              </tr>
            </table>
          </div>
          
          
          
          
          