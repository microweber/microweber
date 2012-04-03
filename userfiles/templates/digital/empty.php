<?php
/*
Template Name: Empty
*/
?>


<?php


get_header(); ?>

		<div id="container">
			<div id="content" role="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



                                                            <?php
    $id = $post->ID;
    $slug = $post->post_name;

    $filename = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/digital/includes/include_'.$id.'.php';
    $filename2 = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/digital/includes/'.$slug.'.php';


     //echo $slug;


    if (file_exists($filename)) {
        include ($filename);
    }

    if (file_exists($filename2)) {
        include ($filename2);
    }

?>

<?php //include $filename2 ?>

<?php endwhile; // end of the loop. ?>


			</div><!-- #content -->
		</div><!-- #container -->


<?php get_footer(); ?>
