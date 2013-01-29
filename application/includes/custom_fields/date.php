<?

$rand = uniqid();


?>

<div class="mw-custom-field-group">
  <label class="mw-custom-field-label" ><? print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="text"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>" id="date_<? print $rand; ?>" placeholder="<? print $data["custom_field_value"]; ?>">
  </div>
</div>


 <script>
    mw.require("{TEMPLATE_URL}css/datepicker.css");

   typeof $.fn.datepicker !='function' ?  mw.require("{TEMPLATE_URL}js/bootstrap-datepicker.js") : '';


 </script>
 <script>
    $(document).ready(function(){
      mw.$( "#date_<? print $rand; ?>" ).datepicker();
    });
 </script>
