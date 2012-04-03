<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

		<div id="primary" class="widget-area" role="complementary">
			<ul class="xoxo">


            <li>
       
                <h3 class="widget-title">Categories</h3>
                <div class="side_cats">
                    <ul>
                    <?php wp_list_categories('child_of=3&title_li='); ?>
                    </ul>
                </div>
            </li>
            <li>
            <h3 class="widget-title">Recents Posts</h3>
                <?php query_posts('showposts=5&cat=3'); ?>
<ul>
<?php while (have_posts()) : the_post(); ?>
<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
<?php endwhile;?>
</ul>
            </li>



<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>



			<li id="archives" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Archives', 'twentyten' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>



		<?php endif; // end primary widget area ?>
			</ul>
		</div><!-- #primary .widget-area -->

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

		<div id="secondary" class="widget-area" role="complementary">
			<ul class="xoxo">
				<?php //dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->

<?php endif; ?>
