<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">


            <div class="wrapper">

     <div class="c" style="padding-bottom: 30px">&nbsp;</div>
   <div class="b bwhite">
      <div class="bt">&nbsp;</div>
      <div class="bm">

      <div class="blogX">



            <div class="side_left">

			<?php
			/* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
			get_template_part( 'loop', 'single' );
			?>



             </div>

             <div class="side_right">
                             <br /><br /><br />  
             <?php get_sidebar(); ?>


             </div>


                <div class="c">&nbsp;</div>



                 </div>




    </div>



       <div class="bb">&nbsp;</div>
    </div>

    <div class="c" style="padding-bottom: 30px">&nbsp;</div>
    </div>






			</div><!-- #content -->
		</div><!-- #container -->


<?php get_footer(); ?>
