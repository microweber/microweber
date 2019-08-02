<?php only_admin_access() ?>

<?php







$check = __mw_check_core_system_update();
 if($check and is_array($check)){
    ?>

    <a  class="active" href="<?php print admin_url(); ?>view:settings#option_group=updates"  >
        <span class="mai-notification"><sup class="mw-notification-count">new</sup></span> <strong>
             <?php _e("Updates"); ?>
        </strong>
    </a>




     <script>
         $( document ).ready(function() {
             if($('.mw-admin-dashboard-main').length){

                 mw.modal({
                     height:300,
                     width:300,
                     content: $('#mw-js-update-modal-notification').html()
                 });


             }
         })


     </script>


<div id="mw-js-update-modal-notification" style="display: none">
    New version is available.

    <a class="mw-ui-btn" href="<?php print admin_url(); ?>view:settings#option_group=updates">Please click here to update</a>
</div>
<?php
}


