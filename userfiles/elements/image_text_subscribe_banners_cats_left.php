<img class="element element-image" width="100%" src="<?php print pixum(800,120); ?>" />
<div class="row">
    <div class="column" style="width: 60%">
        <h2 class="element layout-title lipsum">Simple text</h2>
        <div class="element">
            <p class="element layout-paragraph lipsum"><?php print lipsum(); ?></p>
            <p class="element layout-paragraph lipsum"><?php print lipsum(); ?></p>
            <p class="element layout-paragraph lipsum"><?php print lipsum(); ?></p>
        </div>
    </div>
    <div class="column" style="width: 40%">
        <module type="newsletter" />
        <div class="row">
            <div class="column" style="width: 50%">
                <img class="element element-image layout-img" style="margin-top: 0;" src="<?php print pixum(200,200); ?>" />
                <br />
                <img class="element element-image layout-img" src="<?php print pixum(200,200); ?>" />
            </div>
            <div class="column" style="width: 50%">
                <module type="categories" for="content" />
            </div>
        </div>
    </div>
</div>