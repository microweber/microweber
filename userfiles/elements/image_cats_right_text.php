
<img class="element element-image" width="100%" src="<?php print pixum(800,120); ?>" />

<div class="row">
  <div class="mw-col" style="width: 70%">
    <h2 class="element layout-title lipsum">Simple Text</h2>
    <p class="element lipsum layout-paragraph">
        <?php print lipsum(); ?>
    </p>
  </div>
  <div class="mw-col" style="width: 30%;">
    <module type="newsletter" />
    <div class="row">
        <div class="mw-col" style="width: 50%">
            <img class="element element-image layout-img" style="margin: 0;" alt="" src="<?php print pixum(200,200); ?>" />
            <br />
            <img class="element element-image layout-img" alt="" src="<?php print pixum(200,200); ?>" />
        </div>
        <div class="mw-col" style="width: 50%">
            <module type="categories" for="content" />
        </div>
    </div>
  </div>
</div>
