<?php

/*

type: layout

name: Categories with pictures  



*/
?>
<div class="mw-row picture-with-categories-layout">
    <div class="mw-col" style="width: 60%">
      <div class="mw-col-container">
        <h3 class="element">Title</h3>
        <img src="<?php print pixum(600, 300); ?>" class="element" alt="" width="100%" />
      </div>
    </div>
    <div class="mw-col" style="width: 40%">
      <div class="mw-col-container">
        <h3 class="element">Categories</h3>
        <div class="item-box pad2"><module type="content_categories" id="<?php print uniqid('content_categories_'.rand()); ?>" /></div>
      </div>
    </div>
</div>