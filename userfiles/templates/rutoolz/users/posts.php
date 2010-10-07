 <!--SUBNAV-->
<?php include ACTIVE_TEMPLATE_DIR."users/post.js.php" ?>
<? $layout = $this->core_model->getParamFromURL ( 'layout' );  ?>
<? if($layout != 'small_posts'): ?>

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Pages manager</h1>
    <?php // include ACTIVE_TEMPLATE_DIR."users/posts_header_nav.php" ?>
  </div>

  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a> 
    
    <!--END HELP--> 
  </div>
  <div class="clr"></div>
  <!--END SUBNAV--> 
</div>
<? endif; ?>
<div id="RU-content">
  <? if($layout != 'small_posts'): ?>
  <div class="pad2"></div>
  <? endif; ?>
  <div class="box-holder">
    <div class="box-top">&nbsp;</div>
    <div class="box-inside">
      <div id="campaign_searchbar">
        <div class="campaign_sort">
          <label>By type:</label>
          <select style="width: 100px;">
            <option>Capture pages</option>
            <option>Capture posts</option>
            <option>...</option>
          </select>
          <label>By date:</label>
          <select style="width: 100px;">
            <option>Newest</option>
            <option>Oldest</option>
          </select>
        </div>
        <?php //include ACTIVE_TEMPLATE_DIR. "articles_search_bar.php" ?>
        <?
                    $temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
                 ?>
        <form method="post" action="<?  print  $temp;   ?>" id="campaign_searchform">
          <input name="search_by_keyword" type="text" value="<? if($search_for_keyword): ?><? print $search_for_keyword ?><? else: ?>Search<? endif; ?>" class="type-text" onfocus="this.value=='Search'?this.value='':''" onblur="this.value==''?this.value='Search':''" />
          <input type="submit" value="" class="type-submit" />
        </form>
      </div>
      <? if($layout != 'small_posts'): ?>
      <div class="campaign-header"> <span class="campaign-add-page"><a class="add-page" href="<? print site_url('users/user_action:post/'); ?>">Add page</a></span> </div>
      <? endif; ?>
      <table cellpadding="0" cellspacing="0" class="campaign-table" style="<? if($layout == 'small_posts'): ?>width: 780px<? endif; ?>">
        <colgroup>
        <col width="240" />
        <col width="120" />
        <col width="100" />
        <col width="75" />
        <col width="75" />
        <? if($layout != 'small_posts'): ?>
        <col width="75" />
        <col width="75" />
        <? endif; ?>
        </colgroup>
        <thead>
          <tr>
            <th><span class="left" style="margin-left: 24px;">Page title</span></th>
            <th>Type</th>
            <th>Date</th>
            <? if($layout != 'small_posts'): ?>
            <!--<th>Status</th>-->
            <? endif; ?>
            <th>Preview</th>
            <? if($layout != 'small_posts'): ?>
            <th>Edit</th>
            <th>Delete</th>
            <? endif; ?>
          </tr>
        </thead>
        <tbody>
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
        <!--<ul class="campaign-action">
          <li><a href="#">Delete selected</a></li>
          <li><a href="#">Set status DRAFT</a></li>
          <li><a href="#">Set status Active</a></li>
        </ul>-->
        <?php include ACTIVE_TEMPLATE_DIR."articles_paging.php" ?>
      </div>
    </div>
    <div class="box-bottom">&nbsp;</div>
  </div>
  <? if($layout != 'small_posts'): ?>
  <div class="pad2"></div>
  <? endif; ?>
  <!--END CONTENT--> 
</div>
<? // include "campaigns_edit.php" ?>
