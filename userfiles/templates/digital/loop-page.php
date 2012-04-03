<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-page.php.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.2
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="greyC">
				<div class="wrapper">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <br /><br />
                   <div class="b">
                   <div class="bt">&nbsp;</div>
                   <div class="bm">
						<h1 class="entry-title"><?php the_title(); ?></h1>


					<div class="entry-content manitext">
						<?php the_content(); ?>

						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>

                                                            <?php
    $id = $post->ID;
    $slug = $post->post_name;
    $filename = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/digital/includes/include_'.$id.'.php';
    $filename2 = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/digital/includes/'.$slug.'.php';

    if (file_exists($filename)) {
        include ($filename);
    }

    if (file_exists($filename2)) {
        include ($filename2);
    }

?>

					</div><!-- .entry-content -->



                   </div>
                   <div class="bb">&nbsp;</div>
                 </div>
                 <br /><br />
				</div><!-- #post-## -->
				</div>
				</div>

				<?php //comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>