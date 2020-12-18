<div class="magic-rotator-slide magic-rotator-slide-type-default">
  <div class="mw-wrapper">
    <div class="mw-ui-row-nodrop">
        <div class="mw-ui-col" style="width: 30%">
            <div class="mw-ui-col-container">
                <?php if(isset($slide['images'][0])){ ?>
                    <img src="<?php print $slide['images'][0]; ?>" alt="" />
                <?php } ?>
            </div>
        </div>
        <div class="mw-ui-col" style="width: 40%">
            <div class="mw-ui-col-container">
                <h1 class="magic-rotator-primary">
                  <?php if(isset($slide['primaryText'])){ ?>
                      <?php print $slide['primaryText']; ?>
                  <?php } ?>
                </h1>
                <p class="magic-rotator-secondary"><span class="sm-icon-bag"></span>
                    <?php if(isset($slide['secondaryText'])){ ?>
                      <?php print $slide['secondaryText']; ?>
                    <?php } ?>
                </p>
                <a class="mw-ui-btn mw-ui-btn-big mw-ui-btn-invert" href="<?php if(isset($slide['url'])){print $slide['url'];} ?>"><?php print $slide['seemoreText']  ?></a>
            </div>
        </div>
        <div class="mw-ui-col" style="width: 30%">
            <div class="mw-ui-col-container">
                <?php if(isset($slide['images'][1])){ ?>
                    <img src="<?php print $slide['images'][1]; ?>" alt="" />
                <?php } ?>
            </div>
        </div>
    </div>
  </div>
</div>