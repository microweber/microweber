<?php if($section == 'dashboard'){ ?>

 <div class="mw-helpinfo semi_hidden">
           <div class="mw-help-item" data-for="#mw_toolbar_logo" data-pos="bottomleft">
                <div >
                  <img src="<?php print mw_includes_url(); ?>img/helpinfo/logo.png" alt=""  class="mwhelpinfoimg" />
                  <p style="width: 320px; padding-top: 24px">Welcome to <br>Microweber Admin Panel</p>
                </div>
           </div>
           <div class="mw-help-item" data-for=".dashboard_stats svg" data-pos="topcenter" data-css="margin-top:100px;">
           <div style="width: 605px;">
               <img src="<?php print mw_includes_url(); ?>img/helpinfo/stat.png" alt="" class="mwhelpinfoimg" />
               <div style="padding-top: 24px">
                 <p>This is your traffic statistic.</p>
                 <p>You can explore your visits by daily, weekly and monthly time frame. </p>
               </div>
           </div>
           </div>
           <div class="mw-help-item" data-for="#real_users_online" data-pos="rightcenter">
             <div style="width: 360px;">
                 <img src="<?php print mw_includes_url(); ?>img/helpinfo/users.png" alt=""  class="mwhelpinfoimg" />
                 <p style="padding-top: 24px">See how many people are online <br>on your website right now.</p>
             </div>
           </div>
           <div class="mw-help-item" data-for=".mw-toolbar-notification" data-pos="bottomright" data-css="margin-left:17px;">
               <img src="<?php print mw_includes_url(); ?>img/helpinfo/notifications.png" alt="" class="mwhelpinfoimg" />
               <p style="padding-top: 24px;">Here you'll be able to track your notifications</p>

           </div>
           <div class="mw-help-item" data-for="#mw-go_livebtn_admin" data-pos="bottomright" data-onnext="nextpage('<?php print admin_url('view:content'); ?>');">
              <div style="width: 320px;">
                <img src="<?php print mw_includes_url(); ?>img/helpinfo/live_edit.png" alt="" class="mwhelpinfoimg" />
                <div style="float: right;width: 200px;">
                <p style="padding-bottom: 10px;">Have fun with the Live Edit</p>

               <ul>
                    <li>Add new things</li>
                    <li>Edit your content</li>
                    <li>Make awesome design</li>
               </ul>
           </div> </div>
           </div>
     </div>
<?php  } else if($section == 'content'){ ?>
<div class="mw-helpinfo semi_hidden">

           <div class="mw-help-item" data-for="#pages_tree_toolbar" data-pos="righttop">
             <div  style="width: 500px;">
               <img src="<?php print mw_includes_url(); ?>img/helpinfo/tree.png" class="mwhelpinfoimg" alt="" />
               <p style="padding-top: 24px">Your Website Tree is the best way to manage your <br> Posts, Products, Categories & Pages
               </p>
           </div>
           </div>

           <div class="mw-help-item" data-for="#mw_admin_posts_sortable" data-pos="topcenter">
           <div  style="width: 400px;">
                 <img src="<?php print mw_includes_url(); ?>img/helpinfo/posts.png" class="mwhelpinfoimg" alt="" />
                 <p style="padding-top: 24px">Here are your Posts & Products. <br>
                 Track also your comments from here.</p>
           </div>
           </div>
           <div class="mw-help-item" data-for="#action_new_page button" data-pos="rightcenter" data-onshow="addnewpage()">
                <div  style="width: 295px;">
                <img src="<?php print mw_includes_url(); ?>img/helpinfo/add_new.png" class="mwhelpinfoimg" alt="" />
                 <p style="padding-top: 24px">From here you can  add New Page ... </p>
           </div>
           </div>
           <div class="mw-help-item" data-for=".mw_action_product button" data-pos="rightcenter">
                <div  style="width: 295px;">
                <img src="<?php print mw_includes_url(); ?>img/helpinfo/product.png" class="mwhelpinfoimg" alt="" />
                 <p style="padding-top: 24px">... or Product</p>
           </div>
           </div>

           <div class="mw-help-item" data-for=".mw-template-selector .mw-ui-select" data-pos="topright" >
           <img src="<?php print mw_includes_url(); ?>img/helpinfo/design.png" class="mwhelpinfoimg" alt="" />

               <p style="padding-top: 24px;">Here you can choose the template for your content...</p>
           </div>

           <div class="mw-help-item" data-for=".layouts_box_container" data-pos="topright">
           <img src="<?php print mw_includes_url(); ?>img/helpinfo/layout.png" class="mwhelpinfoimg" alt="" />
               <p style="padding-top: 15px;">Also you can choose the type of <br> your layout(depending on the type of the content)</p>
           </div>
           <div class="mw-help-item" data-for=".module-custom-fields-admin-holder" data-pos="topcenter" data-onbeforeshow="showcustomfields()">
               <div style="width: 410px;">
                <img src="<?php print mw_includes_url(); ?>img/helpinfo/price.png" class="mwhelpinfoimg" alt="" />

               <p style="padding-top: 15px;">Lets customize your forms or products. </p>
               <p>Add price, color, size and many more.</p>
               <p style="padding-top:10px;"><a href="https://www.youtube.com/watch?v=cOZhe_WtnWM" target="_blank" class="mw-ui-link">See this video</a></p>

               </div>

           </div>
           <div class="mw-help-item" data-for=".admin-thumbs-holder" data-pos="rightcenter" data-onbeforeshow="showcustompics()" data-onnext="nextpage('<?php print admin_url('view:shop'); ?>')">
               <p>Click to create gallery for your page</p>
               <small>Note that you have to drop the Gallery Module in the Live Edit section from the Modules Toolbar</small>
           </div>
</div>

<?php } else if($section == 'shop'){ ?>

<div class="mw-helpinfo semi_hidden">
     <div class="mw-help-item" data-for=".new-order-notification" data-pos="bottomleft">
          <div style="width: 400px;">
               <img src="<?php print mw_includes_url(); ?>img/helpinfo/product.png" class="mwhelpinfoimg" alt="" />
               <p style="padding-top: 30px">Your new orders will wait for you here.</p>
          </div>

     </div>
    <div class="mw-help-item" data-for="#mw-admin-shop-navigation" data-pos="bottomcenter" data-onnext="nextpage('<?php print admin_url('view:modules'); ?>')">
         <div style="width: 700px;">
             <img src="<?php print mw_includes_url(); ?>img/helpinfo/settings.png" class="mwhelpinfoimg" alt="" />
             <p style="padding-top: 24px">The "Shop" section is similar to the "Website" section but it has a navigation, <br> that will help you to manage your Store/s . </p>
         </div>
     </div>

</div>


<?php } else if($section == 'modules'){ ?>

       <div class="mw-helpinfo semi_hidden">

            <div class="mw-help-item" data-for="#modules_admin_mw_modules_admin_wrapper" data-pos="topcenter"  data-onnext="nextpage('<?php print admin_url('view:settings'); ?>')">
                <p>Welcome to the "Modules" section</p>
                <p>Here you can Explore, Edit or delete your modules.</p>
            </div>

        </div>

<?php } else if($section == 'users'){ ?>
<div class="mw-helpinfo semi_hidden">


 <div class="mw-help-item" data-for="#users_admin_panel" id="mwhelpinfouser1" data-pos="topcenter" data-onnext="viewuser()">
     <p>View and manage your users information. </p>
 </div>
 <div class="mw-help-item" data-for=".mw-ui-box-header" data-pos="topcenter">
     <p>View and manage your users information.</p>
 </div>

 <div class="mw-help-item" data-for="#mw_edit_page_left .mw-ui-btn-green" data-pos="rightcenter" data-onnext="final()">
     <p>Add New users to your awesome website. </p>
 </div>



</div>

<?php  } else if($section == 'settings'){ ?>

<div class="mw-helpinfo semi_hidden">


<div class="mw-help-item" data-for="#mw_tabs .active" data-pos="bottomcenter" data-css="margin-top:-2px;">
     <p>The "Settings" section will help you to manage the global settings for your website </p>
 </div>
 <div class="mw-help-item" data-for="#mw_edit_page_left" data-pos="righttop" data-onnext="nextpage('<?php print admin_url('view:users'); ?>')">
     <p>This is the navigation with the available settings</p>
 </div>


</div>

<?php } else if($section == 'liveedit'){ ?>

<div class="mw-helpinfo semi_hidden">





</div>

<?php } ?>



<script type="text/javascript">
   mw.require("helpinfo.js", true);
   mw.require("<?php print mw_includes_url(); ?>css/helpinfo.css", true);
</script>

<script>
  mw.helpinfo.functions.addnewpage = function(){
      mw.mouse.gotoAndClick("#action_new_page button");
      //mw.helpinfo.pause();
  }

  mw.helpinfo.functions.addnewproduct = function(){
      mw.mouse.gotoAndClick(".mw_action_product button");
  }

  mw.helpinfo.functions.showcustomfields = function(){
      var item = mwd.getElementById('custom-fields-toggler');
      $(item).addClass("custom-fields-toggler");
      $($(item).dataset("for")).show();
  }
  mw.helpinfo.functions.showcustompics = function(){
      var item = mwd.getElementById('pictures-toggle');
      $(item).addClass("custom-fields-toggler");
      $($(item).dataset("for")).show();
  }

  mw.helpinfo.functions._viewuser = false;

  mw.helpinfo.functions.viewuser = function(){
        if(!mw.helpinfo.functions._viewuser){
          mw.helpinfo.functions._viewuser = true;
          setTimeout(function(){
            mw.$("#users_admin_panel tbody tr:first").addClass("active");
            mw.mouse.gotoAndClick("#users_admin_panel tbody .mw-ui-btn");
            mw.$(".module-users-edit-user").addClass("mwcurrhelp");
            mw.$("#mwhelpinfouser1").remove();
            mw.helpinfo.next()

        }, 300);
        }
        else{
            mw.helpinfo.active = $(mw.helpinfo.active).next().length > 0 ? $(mw.helpinfo.active).next() : mw.$(".mw-help-item").eq(0);
            mw.helpinfo.init();
        }


  }

  mw.helpinfo.functions.final = function(){
    mw.helpinfo.HideToHelp();
  }

</script>

<div id="HELPINFOGHOST" style="display: none;">
    <div style="width: 320px;">
      <img src="<?php print mw_includes_url(); ?>img/helpinfo/help.png" class="mwhelpinfoimg" alt="" />
      <p style="padding-top: 30px;">I'll be here if you need me.</p>
    </div>
</div>