<script type="text/javascript">
	 $(document).ready(function(){
		$("#datepicker_custom_field_event_start").datepicker({ 
		dateFormat: 'yy-mm-dd',
		<?php if(strval($form_values['custom_fields']['event_start']) != ''): ?>
		defaultDate: '<?php print  $form_values['custom_fields']['event_start']; ?>' ,
		<?php endif; ?>
		altField: '#custom_field_event_start' ,
			onSelect: function(dateText, inst) {
			$check_for_the_past = $("#custom_field_event_end").val();
			//alert(dateText);
			if($check_for_the_past < dateText){
			//	$("#datepicker_custom_field_event_end").datepicker('option', 'minDate', dateText);
				//$("#datepicker_custom_field_event_end").datepicker('option', 'defaultDate', dateText);
				
			}
			
			}
		 });
	});
	 $(document).ready(function(){
		$("#datepicker_custom_field_event_end").datepicker({ 
		dateFormat: 'yy-mm-dd',
		<?php if(strval($form_values['custom_fields']['event_end']) != ''): ?>
		defaultDate: '<?php print  $form_values['custom_fields']['event_end']; ?>' ,

		
		<?php endif; ?>

		//set up min date
		<?php if(strval($form_values['custom_fields']['event_start']) != ''): ?>
		minDate: '<?php print  $form_values['custom_fields']['event_start']; ?>' ,
		<?php endif; ?>




		altField: '#custom_field_event_end'
		 });
	});
	</script>
    <div style="position: relative">
    <label class="lbl">Event Start</label>
      <input onfocus="$('#datepicker_custom_field_event_start').show()" onblur="$('#datepicker_custom_field_event_start').hide()" style="width: 200px" name="custom_field_event_start" id="custom_field_event_start" type="text" value="<?php print  $form_values['custom_fields']['event_start']; ?>" />
      <div id="datepicker_custom_field_event_start" style="position: absolute;width: 300px;top:0px;left:210px;display: none"></div>
    </div>
    <div style="position: relative">
    <label class="lbl">Event End</label>
      <input onfocus="$('#datepicker_custom_field_event_end').show()" onblur="$('#datepicker_custom_field_event_end').hide()" style="width: 200px" name="custom_field_event_end" id="custom_field_event_end" type="text" value="<?php print  $form_values['custom_fields']['event_end']; ?>" />
      <div id="datepicker_custom_field_event_end" style="position: absolute;width: 300px;top:0px;left:210px;display: none"></div>
    </div>















