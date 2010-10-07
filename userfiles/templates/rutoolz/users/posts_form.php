<!--SUBNAV-->

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Forms manager</h1>
    <?php // include ACTIVE_TEMPLATE_DIR."users/posts_header_nav.php" ?>
  </div>
  
  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a> 
    
    <!--END HELP--> 
  </div>
  <div class="clr"></div>
  <!--END SUBNAV--> 
</div>
<div id="RU-content">
  <div class="pad2"></div>
  <div class="box-holder">
    <div class="box-top">&nbsp;</div>
    <div class="box-inside">
      <div class="campaign-header"> <span class="campaign-add-page"><a class="add-page" href="<? print site_url('users/user_action:post/'); ?>/type:form">Add form</a></span> </div>
      <table cellpadding="0" cellspacing="0" class="campaign-table">
        <colgroup>
        <col width="240" />
        <col width="120" />
        <col width="100" />
        <col width="75" />
        <col width="75" />
        <col width="75" />
        <col width="75" />
        </colgroup>
        <thead>
          <tr>
            <th><span class="left" style="margin-left: 24px;">Form title</span></th>
            <th>Type</th>
            <th>Date</th>
          
            <th>Preview</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <!--  <tr>
                    <td><input type="checkbox" class="type-checkbox" /><h3 class="mwboxtitle">Page title here</h3></td>
                    <td>Capture</td>
                    <td>12.12.2023</td>
                    <td><span class="statusY">&nbsp;</span></td>
                    <td><span class="magnifier">&nbsp;</span></td>
                    <td><span class="campaign-edit">&nbsp;</span></td>
                    <td><span class="remove">&nbsp;</span></td>
                </tr>
                <tr class="even">
                    <td><input type="checkbox" checked="checked" class="type-checkbox" /><h3 class="mwboxtitle">Page title here</h3></td>
                    <td>Capture</td>
                    <td>12.12.2023</td>
                    <td><span class="statusN">&nbsp;</span></td>
                    <td><span class="magnifier">&nbsp;</span></td>
                    <td><span class="campaign-edit">&nbsp;</span></td>
                    <td><span class="remove">&nbsp;</span></td>
                </tr>-->
          
          <? if(!empty($posts_data['posts'])): ?>
          <? foreach ($posts_data['posts'] as $the_post): ?>
          <? $show_edit_and_delete_buttons = true; ?>
          <?php include ACTIVE_TEMPLATE_DIR."users/articles_list_single_post_item.php" ?>
          <? endforeach; ?>
          <? else : ?>
        <div class="post">
          <p> There are no posts here. Try again later. </p>
        </div>
        <? endif; ?>
          </tbody>
        
      </table>
      <div class="campaign-bot">
      <!--  <ul class="campaign-action">
          <li><a href="#">Delete selected</a></li>
          <li><a href="#">Set status DRAFT</a></li>
          <li><a href="#">Set status Active</a></li>
        </ul>-->
        <?php include ACTIVE_TEMPLATE_DIR."articles_paging.php" ?>
      </div>
    </div>
    <div class="box-bottom">&nbsp;</div>
  </div>
  <div class="pad2"></div>
  <!--END CONTENT--> 
</div>
