<?php dbg(__FILE__); ?>

<div id="home-slider">
  <div id="home-slider-content">
    <div id="home-slide-holder">
      <div id="home-slide-vertical-holder">
        <div class="home-slide-animator">
          <?php $contibutors = $this->users_model->rankingsTopContibutors($user_limit = 100); ?>
          <?php if(!empty($contibutors)): ?>
          <?php $counter = 0; foreach($contibutors as $countertem): ?>
          <?php if($counter == 40): $counter = 20; ?>
          <?php endif; ?>
          <?php if(($counter == 21) or ($counter == 0) ): ?>
          <ul>
            <?php endif; ?>
            <?php $author = $this->users_model->getUserById($countertem); ?>
            <?php $thumb = $this->users_model->getUserThumbnail( $countertem, 69, 69); ?>
            <?php if(!empty($author)): ?>
            <li>
                <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>">
                    <strong style="background-image: url(<?php print $thumb; ?>);">&nbsp;</strong>
                    <span><?php print $temp = $this->users_model->getPrintableName ( $countertem, 'fill' ); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if($counter == 20): ?>
          </ul>
          <?php endif; ?>
          <?php $counter++; endforeach; ?>
          <?php else: ?>
          <ul>
            <li>No top contibutors</li>
          </ul>
          <?php endif; ?>
        </div>
        <div class="home-slide-animator">
          <?php $contibutors = $this->users_model->rankingsTopCommenters($user_limit = 100); ?>
          <?php if(!empty($contibutors)): ?>
          <?php $counter = 0; foreach($contibutors as $countertem): ?>
          <?php if($counter == 40): $counter = 20; ?>
          <?php endif; ?>
          <?php if(($counter == 21) or ($counter == 0) ): ?>
          <ul>
            <?php endif; ?>
            <?php $author = $this->users_model->getUserById($countertem); ?>
            <?php $thumb = $this->users_model->getUserThumbnail( $countertem, 69, 69); ?>
            <?php if(!empty($author)): ?>
            <li>
                <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>">
                    <strong style="background-image: url(<?php print $thumb; ?>);">&nbsp;</strong>
                    <span><?php print $temp = $this->users_model->getPrintableName ( $countertem, 'fill' ); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if($counter == 20): ?>
          </ul>
          <?php endif; ?>
          <?php $counter++; endforeach; ?>
          <?php else: ?>
          <ul>
            <li>Top Commentators</li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <a href="#" id="home-slider-left" onclick="SlideLeft(); return false;">Slide Left</a> <a href="#" id="home-slider-right" onclick="SlideRight(); return false;">Slide Right</a> </div>
  <div id="home-slider-controlls">
    <ul>
      <li class="active"><a href="#"><span>Top Contributors</span></a></li>
      <li><a href="#"><span>Top Commentators</span></a></li>
      <!-- <li><a href="#"><span>Fastest Climbers</span></a></li>
      <li><a href="#"><span>Top Bloggers</span></a></li>
      <li><a href="#"><span>Top Commentators</span></a></li>-->
    </ul>
  </div>
</div>
<!-- /#home-slider -->
<?php dbg(__FILE__, 1); ?>
