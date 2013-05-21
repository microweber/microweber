<?php only_admin_access(); ?><script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<div class="<?php print $config['module_class'] ?>">
<?php $data = get_option('curent_template', 'template',1);

 
 ?>
<script  type="text/javascript">


    function mw_set_default_template(){
        var el1 =  mw.$('.mw-site-theme-selector').find("[name='<?php print  $data['option_key']; ?>']")[0];
        mw.options.save(el1, function(){
          mw.notification.success("<?php _e("Template settings are saved"); ?>.");
        });
    }

    $(document).ready(function(){
        $(window).bind('templateChanged', function(){
          $(".mw-site-theme-selector").find("[name='active_site_template']").each(function( index ) {
            $("#mw_curr_theme_val").val($(this).val());
          });
        });
     });

</script>
<?php  //d($data); ?>

<div class="mw-site-theme-selector">
  <label class="control-label-title"> <?php _e("Website template"); ?> </label>

  <input id="mw_curr_theme_val" name="<?php print  $data['option_key']; ?>"   class="mw_option_field mw-ui-field"   type="hidden" option-group="<?php print  $data['option_group']; ?>"  value="<?php print  $data['option_value']; ?>" data-id="<?php print  $data['id']; ?>" />
  <module type="content/layout_selector" data-active-site-template="<?php print $data['option_value'] ?>" autoload="1"  />
  <button class="mw-ui-btn mw-action-change-template" onClick="mw_set_default_template()"><?php _e("Apply Template"); ?></button>
</div>
</div>