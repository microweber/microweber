<?php dbg(__FILE__); ?>
<?php $widgets = $this->core_model->getCustomFields('table_users', $author['id']); ?>

<div class="sidebar-widgets">

    
    
          <?php if(!empty($widgets)): ?>
          <?php foreach($widgets as $k => $v): ?>
          <?php if(trim($v) != '')  : ?>
          <?php if(stristr($k, 'sidebar_widget_') != FALSE)  : ?>

<div class="the-widget">
    <?php print html_entity_decode(trim($v)); ?>
     <div class='c'>&nbsp;</div>
 
</div>


          
          <?php endif; ?>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
</div>
<?php dbg(__FILE__, 1); ?>