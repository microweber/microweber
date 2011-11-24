
<div id="main">
  <div class="block1">
  	<? if(!empty($active_categories)): ?>
    <? $cat_info = CI::model('taxonomy')->getSingleItem($active_categories[1]);?>
    <h2><? print $cat_info['taxonomy_value'] ?></h2>
    <? if(trim($cat_info['content_body']) != ''): ?>
    <p class="message message-info message-closable"><? print $cat_info['content_body'] ?></p>
    <? endif; ?>
    <? endif; ?>
    
    
    <?php $kw_value =   CI::model('core')->getParamFromURL ( 'keyword' ); 
if($kw_value  != false){ ?>
<h2>Seach results for: <? print $kw_value ?></h2>
<?
}
?>
    
    
    <br />
  </div>
  <?php if(!empty($posts)): ?>
  <?php foreach ($posts as $the_post): ?>
  <?php $more = false;
 $more = CI::model('core')->getCustomFields('table_content', $the_post['id']);
	$the_post['custom_fields'] = $more;
	?>
  <div class="portlet portlet-closable">
    <div class="portlet-header">
      <h4><?php print $the_post['content_title']; ?></h4>
      <ul class="portlet-tab-nav">
        <li class="portlet-tab-nav-active"><a href="#<?php print $the_post['id']; ?>tab1">Description</a></li>
        <? if(trim($more['php']) != ''): ?>
        <li class=""><a href="#<?php print $the_post['id']; ?>tab2">PHP code</a></li>
        <? endif; ?>
        <? if(trim($more['js']) != ''): ?>
        <li><a href="#<?php print $the_post['id']; ?>tab3">Javascript code</a></li>
        <? endif; ?>
      </ul>
    </div>
    <!-- .portlet-header -->
    <div class="portlet-content">
      <div id="<?php print $the_post['id']; ?>tab1" class="portlet-tab-content"> <?php print html_entity_decode( $the_post['content_body'] );  ; ?> </div>
      <? if(trim($more['php']) != ''): ?>
      <div id="<?php print $the_post['id']; ?>tab2" class="portlet-tab-content">
        <pre>
         <code class="php">
     <?php print ( $more['php'] );  ; ?>
     </code>
</pre>
      </div>
      <? endif; ?>
      <? if(trim($more['js'] ) != ''): ?>
      <div id="<?php print $the_post['id']; ?>tab3" class="portlet-tab-content">
        <pre><code class="js">
  <?php print ( $more['js'] );  ; ?>
  </code>
  </pre>
      </div>
      <? endif; ?>
    </div>
    <!-- .portlet-content -->
  </div>
  <!-- .portlet -->
  <?php endforeach; ?>
  <?php endif; ?>
  <?php if(!empty($posts_pages_links)): ?>
  <?php print $page_link ;  ?>
  <ul class="paging">
    <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
    <li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
    <?php $i++; endforeach;  ?>
  </ul>
  <?php endif ; ?>
</div>
<?php include (ACTIVE_TEMPLATE_DIR.'articles_sidebar.php') ?>
