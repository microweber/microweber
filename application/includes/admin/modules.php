<module type="admin/modules" id="mw_modules_admin_wrapper"   />

<?php  if(!isset($_COOKIE['helpinfo'])){  ?>
<div class="mw-helpinfo semi_hidden">


<div class="mw-help-item" data-for="#modules_admin_mw_modules_admin_wrapper" data-pos="righttop"  data-onshow="gotonextpage('<?php print admin_url('view:settings'); ?>')">
     <p>Welcome to the "Modules" section</p>
     <p>Here you can Explore, Edit or delete your modules.</p>
 </div>


</div>

     <script>
         mw.require("helpinfo.js");
         mw.require("<?php print INCLUDES_URL; ?>css/helpinfo.css");
     </script>



<?php  }  ?>
