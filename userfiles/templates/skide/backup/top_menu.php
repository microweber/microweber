<div id="megadropdown">
  <ul>
    <?php $menu_items = CI::model('content')->getMenuItemsByMenuUnuqueId('header_menu');	?>
    <?php foreach($menu_items as $item): ?>
    <li <?php if($item['is_active'] == true): ?>  class="current"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
    <?php endforeach ;  ?>
 <?php /* <li  class="current"> <a href="">Dashboard</a> </li>
    <li class=""> <a href="javascript:;">Content</a>
      <div class="subNav">
        <div class="col">
          <h3>Search Engines</h3>
          <ul>
            <li><a href="http://google.com" target="_blank">Google</a></li>
            <li><a href="http://yahoo.com" target="_blank">Yahoo</a></li>
            <li><a href="http://bing.com" target="_blank">Bing</a></li>
            <li><a href="http://ask.com" target="_blank">Ask</a></li>
          </ul>
        </div>
        <!-- .col -->
      </div>
      <!-- .subNav -->
    </li>
    <li> <a href="javascript:;">Comments</a>
      <div class="subNav">
        <div class="col">
          <ul>
            <li><a href="javascript:;">CSS Tutorials</a></li>
            <li><a href="javascript:;">HTML Tutorials</a></li>
            <li><a href="javascript:;">Javascript Tutorials</a></li>
            <li><a href="javascript:;">PHP Tutorials</a></li>
          </ul>
        </div>
        <!-- .col -->
      </div>
      <!-- .subNav -->
    </li>
    <li> <a href="javascript:;">Settings</a>
      <div class="subNav">
        <div class="col">
          <h3>Help Links</h3>
          <ul>
            <li><a href="javascript:;">Section #1</a></li>
            <li><a href="javascript:;">Section #2</a></li>
            <li><a href="javascript:;">Section #3</a></li>
            <li><a href="javascript:;">Section #4</a></li>
          </ul>
        </div>
        <!-- .col -->
      </div>
      <!-- .subNav -->
    </li>
  </ul>*/ ?>
</div>
