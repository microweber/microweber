<?

$rand = uniqid();


?>

<div class="control-group">
  <label class="custom-field-title"><? print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="text"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>" id="date_<? print $rand; ?>" placeholder="<? print $data["custom_field_value"]; ?>">
  </div>
</div>


 <script>
    mw.require("datepicker.css", true);
    mw.require("datepicker.js", true);
 </script>
 <script>
    $(document).ready(function(){
      mw.$( "#date_<? print $rand; ?>" ).datepicker();

    });
 </script>
