
<img class="element img-polaroid" width="100%" src="<?php print pixum(800,120); ?>" />

<div class="mw-row">
  <div class="mw-col" style="width: 70%">
      <div class="mw-col-container">
        <h2 class="element layout-title lipsum">Simple Text</h2>
        <p class="element lipsum layout-paragraph">
            <?php print lipsum(); ?>
        </p>
      </div>
  </div>
  <div class="mw-col" style="width: 30%;">
    <div class="mw-col-container">
      <module type="newsletter" />
      <div class="mw-row">
          <div class="mw-col" style="width: 50%">
            <div class="mw-col-container">
                <img class="element img-polaroid img-rounded" alt="" src="<?php print pixum(200,200); ?>" />
                <br />
                <img class="element img-polaroid img-rounded" alt="" src="<?php print pixum(200,200); ?>" />
            </div>
          </div>
          <div class="mw-col" style="width: 50%">
            <div class="mw-col-container">
                <module type="categories" for="content" />
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
