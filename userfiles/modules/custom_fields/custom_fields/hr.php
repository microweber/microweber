<?

$rand = rand();

$val = $data["custom_field_value"];

?>

<div class="mw-custom-field-group">

  <div class="mw-custom-field-form-controls">
    <?php if($val=='hr'){   ?>
        <hr class="mw-ui-custom-fields-section-break" />
  <?php  } else if($val=='space'){   ?>
        <div class="vSpace mw-ui-custom-fields-section-break">&nbsp;</div>
  <?php   }  ?>
  </div>
</div>
