<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

                <div class="wrapper blog-ec">

     <div class="c" style="padding-bottom: 30px">&nbsp;</div>

               <div class="b bwhite">
      <div class="bt">&nbsp;</div>
      <div class="bm">

                 <div class="blogX">
				<div class="manitext">

				<h2 class="page-title" style="margin: 0"><?php
					printf( __( 'Category Archives: %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></h2> </div>

                <div class="side_left">
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'category' );
				?>
                </div>

                <div class="side_right">
                <?php get_sidebar(); ?>
                </div>

                </div>
                <div class="c">&nbsp;</div>
             </div>
                <div class="bb">&nbsp;</div>
                </div>
                </div>

                <br /><br />
			</div><!-- #content -->
		</div><!-- #container -->


<?php get_footer(); ?>
