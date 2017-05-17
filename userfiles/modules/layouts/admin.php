<?php only_admin_access(); ?>

<div class="module-live-edit-settings"> 
  <script>
        $(document).ready(function(){
            window.TTABS = window.TTABS || mw.tabs({
              nav:"#ttabnav a",
              tabs:".ttab"
            });

        })

    </script>
  <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="ttabnav"> <a class="mw-ui-btn active" href="javascript:;">Explore</a> <a class="mw-ui-btn" href="javascript:;">Options</a> </div>
  <div class="mw-ui-box mw-ui-box-content">
    <div class="ttab" style="display: block;">
      <module type="admin/modules/templates" />
    </div>
    <div class="ttab"> </div>
    <div class="ttab"> </div>
  </div>
</div>
