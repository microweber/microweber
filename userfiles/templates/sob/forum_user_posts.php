<?php dbg(__FILE__); ?>
<?php if(!isset($author['id']) && empty($author['id']))
        exit;

    include_once(ACTIVE_TEMPLATE_DIR . '/rss/simplepie.inc');
    
    // Parse it
    $feed = new SimplePie();
    
    $feed->set_feed_url(site_url('forum/rss.php/profile') . '/' . $author['id']);

    $feed->enable_cache(false);	//disable caching
    $feed->init();

    $feed->handle_content_type();
?>

<?php if ($feed->data): ?>
    <div class="popular-discussions">
        <div class="pd-ctrl-wrapper">
            <div class="wrap-pd-ctrl" style="margin: 2px 12px 2px 0;"> 
                <span class="pd-left">&laquo;</span> 
                <span class="pd-ctrl active">1</span> 
                <span class="pd-ctrl">2</span> 
                <span class="pd-ctrl">3</span> 
                <span class="pd-right">&raquo;</span> 
                <span class="pd-dot"></span> 
            </div>
        </div>
  
        <div class="popular-slider-holder">
            <div class="popular-slider">
            <?php $items = $feed->get_items(0, 30); ?>
                <?php $i = 0; foreach($items as $item): ?>
                    <?php $forum_poster_username = false;
                        if($forum_poster = $item->get_author())
                            $forum_poster_username =  $forum_poster->get_name(); 
                        
                        $who = $this->users_model->getIdByUsername($forum_poster_username); 
                        $who = $this->users_model->getUserById($who); 
                        $who_name = $this->users_model->getPrintableName($who['id'], 'full');
                    ?>
                
            <?php if($i == 0): ?> 
                <ul> 
            <?php endif; ?>

                    <li>
                        <div> 
                        <?php $thumb = $this->users_model->getUserThumbnail($who['id'], 50); ?>
                        <?php if($thumb != ''): ?>
                            <span class="img" style="background-image: url('<?php print $thumb; ?>')"></span> 
                        <?php endif; ?>
                        
                            <span class="p_holder"> 
                                <span class="pop-title">
                                    <a href="<?php print site_url('userbase/action:profile/username:' . $who['username']); ?>"><?php print $who_name ?></a> on 
                                    <a href="<?php echo  urldecode($item->get_permalink()); ?>"><?php echo character_limiter($item->get_title(), 35); ?></a>
                                </span> 
                                <strong>
                                    <a href="<?php echo  urldecode($item->get_permalink()); ?>"></a>
                                </strong> 
                       
                                <?php echo $item->get_date('j M Y'); ?>
                            </span> 
                        </div>
                    </li>

            <?php $i++; if($i == 10): ?>
                </ul>
            <?php $i = 0; endif; ?>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <a id="get-rss-home" class="get-rss" href="<?php print site_url('forum/rss.php') ?>">Get forum RSS</a> 
    <a class="btn right wmarg" href="<?php print site_url('forum') ?>">More Discussions</a> 
    <br class="c"/>

<?php endif; ?>

<?php dbg(__FILE__, 1); ?>
