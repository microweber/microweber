<?php $this_field_name = (!isset($field_name) or !$field_name) ? "mw_raw_output":$field_name;  ?>

<ul class="nav nav-tabs" id="<?php print $this_field_name ?>_holder">
  <li class="active"><a href="#<?php print $this_field_name ?>">Results</a></li>
  <li><a href="#<?php print $this_field_name ?>_src">PHP Code</a></li>
  <li><a href="#<?php print $this_field_name ?>_other">Other</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="<?php print $this_field_name ?>"></div>
  <div class="tab-pane" id="<?php print $this_field_name ?>_src"></div>
  <div class="tab-pane" id="<?php print $this_field_name ?>_other">other</div>
</div>
<script>
  $(function () {
 
	  $('#<?php print $this_field_name ?>_holder a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
     
  })
</script> 