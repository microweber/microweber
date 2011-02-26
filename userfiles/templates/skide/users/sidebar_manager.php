<?php dbg(__FILE__); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
<?php $more = CI::model('core')->getCustomFields('table_users', $form_values['id']); ?>

<div id="profile-main">
  <?php $compete_profile = CI::model('core')->getParamFromURL ( 'compete_profile' ); ?>
  <?php if($user_edit_done == true) : ?>
  <h1 class="saved">Changes are saved</h1>
  <?php endif;  ?>
  <?php if($compete_profile == 'yes') : ?>
  <h1 id="profile-please-complete">Please complete your profile</h1>
  <?php endif;  ?>
  <?php if(!empty($user_edit_errors)) : ?>
  
  <!--<ul class="error">
      <?php foreach($user_edit_errors as $k => $v) :  ?>
      <li><?php print $v ?></li>
      <?php endforeach; ?>
    </ul>--> 
  
  <script type="text/javascript">
        var errors = '<ul class="error"><?php foreach($user_edit_errors as $k => $v) :  ?><li><?php print $v ?></li><?php endforeach; ?></ul>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });
        });

    </script>
  <?php endif ?>
  <?php if($user_edit_done == true) : ?>
  <script type="text/javascript">
        var errors = ' <h2>Sidebar updated!</h2>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });





        });



    </script>
  <?php endif ?>
  <form action="<?php print site_url('users/user_action:sidebar-manager'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="validate">
    <div id="more-websites">
    <div style="padding: 15px 0 0 0;">
            <a href="#add-new-widget-helper" class="master-help right" style="margin-top: 6px;">What is this?</a>
            <a href="#" class="btn new-widget" onclick="mw.NewWidtget()"><strong>Add new Sidebar widget</strong></a>
            <div class="master-help" id="add-new-widget-helper">
<p>You can easily embed a video, a picture, an external link or any other HTML code here. Your content will appear on the right side of your profile and all of your SOB pages.
If you wish to remove a specific widget, simply delete the corresponding HTML code you've embedded and hit "Save changes".</p>
<p>You might want to share several interesting videos, or may be a map with your current location, or a graph with the stock market values. The choice is yours. All you need to do to use this widget is find the corresponding HTML code for the information you want to share. These are usually found under the description "Embed".
For example, if you have at least once used YouTube you might have seen this option right under every single video displayed on the website. If you hit the "Embed" button an HTML code instantly appears. If you copy it and paste it in the SOB Widget box, the video will instantly appear on your sidebar.
</p>
            </div>
            <table id="create-new-widget" class="abshidden" style="width: 0">
                <tr class="widget-table-row">
                   <td class="widget-sors">
                        <strong>Sidebar widget:</strong>
                        <span class="larea">
                            <textarea class="widget-area" name="custom_field_sidebar_widget_"></textarea>
                        </span>
                   </td>
                   <td class="widget-preview">&nbsp;</td>
                </tr>
            </table>

    </div>

         <div id="widget-table-wrapper">
           <table cellpadding="0" cellspacing="0" id="widget-table" width="100%">
            <tbody id="widget-table-body">

          <?php if(!empty($more)): ?>

            <?php foreach($more as $k => $v): ?>
            <?php if(trim($v) != '')  : ?>
            <?php if(stristr($k, 'sidebar_widget_') != FALSE)  : ?>
            <tr class="widget-table-row">
               <td class="widget-sors">

                    <strong>Sidebar widget:</strong>
                    <span class="larea">
                        <textarea class="widget-area" name="custom_field_<?php print($k); ?>"><?php print (trim($v)); ?></textarea>
                    </span>

               </td>
               <td class="widget-preview">
                  <?php print html_entity_decode($v); ?>
               </td>
            </tr>

          <?php endif; ?>
          <?php endif; ?>
          <?php endforeach; ?>


          <?php endif; ?>
            </tbody>
               </table>
           </div>
        </div>
    <a href="#" class="btn submit">Save changes</a>
    <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
  </form>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
<?php dbg(__FILE__, 1); ?>
