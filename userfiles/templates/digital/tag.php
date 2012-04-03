<?php
/**
 * The template for displaying Tag Archive pages.
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
				<div class="manitext"><h2 style="margin-top:0 " class="page-title"><?php
					printf( __( 'Tag Archives: %s', 'twentyten' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				?></h2></div>


<div class="side_left">
<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
 get_template_part( 'loop', 'tag' );




?>

  </div>

<div class="side_right">

             <?php get_sidebar(); ?>


             </div>


<div class="c">&nbsp;</div>

</div>
</div>
<div class="bb">&nbsp;</div>
</div>

			</div><!-- #content -->
		</div><!-- #container -->
		</div><!-- #container -->


<?php get_footer(); ?>
