
<div class="wrapper blog-ec">

     <div class="c" style="padding-bottom: 30px">&nbsp;</div>
   <div class="b bwhite">
      <div class="bt">&nbsp;</div>
      <div class="bm">

      <div class="blogX">

           <div class="manitext"><h2 style="margin-top: 0">Blog</h2></div>

                  <div class="">



            <div class="side_left">



           <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
// The Query
query_posts( '&posts_per_page=12&cat=3&paged=' . $paged);

// The Loop
while ( have_posts() ) : the_post();   ?>
 <div class="blog_list">
    <a class="prelink" href="<?php the_permalink(); ?>">
        <img src="<?php echo home_url(); ?>/image.php/?width=200&amp;height=200&amp;image=<?php echo first_img(); ?>" />
    </a>

  <h2><a href="<?php the_permalink(); ?>"><?php	the_title(); ?></a></h2>

  <?php the_excerpt(); ?>
</div>

 <?php  endwhile; ?>


 <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
 <?php


  wp_pagenavi();



wp_reset_query();

?>



 </div>


 </div>
 <div class="side_right">

             <?php get_sidebar(); ?>


             </div>

            <div class="c" >&nbsp;  </div>

      </div>




    </div>



       <div class="bb">&nbsp;</div>
    </div>



    <div class="c" style="padding-bottom: 30px">&nbsp;</div>


</div>
