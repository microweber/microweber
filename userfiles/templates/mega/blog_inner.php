<?php

/*

  type: layout
  content_type: static
  name: Blog Inner
  description: Blog Inner layout

*/

?>

 <?php include "header.php"; ?>
    <div class="container">

           <div class="main">

            <div class="row">
                <div class="span9">


                <div class="bbox">
                <div class="bbox-content">

                <h3 class="blue edit" rel="content" field="title"></h3>


                <div class="post-info">


                            <small class="muted"><?php
                              $date = new DateTime($content['created_on']);
                              print $date->format('F d, Y');
                            ?></small>

                         <?php $author = get_user($content['created_by']);  ?>

                         <span class="post-author">
                             <img src="<?php print thumbnail($author['thumbnail'], 19, 19); ?>" alt="" />
                             <span><?php print user_name($content['created_by']); ?> </span>
                         </span>

                         <?php $cats = mw('category')->get_for_content($content['id']);  ?>
                         <?php
                             if(is_array($cats)){
                                 $html = '<span class="post-cats"><i class="icon-tag"></i>';
                                 foreach($cats as $cat){
                                    $html .= '<a class="muted" href="'.category_link($cat['id']).'">'.$cat['title'].'</a>, ';
                                 }
                                 $html .= '</span>';
                                 print $html;
                              }
                          ?>

                </div>




                <p><img src="<?php print get_picture($content['id']); ?>" alt="<?php print $content['title'] ?>" title="<?php print $content['title'] ?>" /></p>


                <div class="edit" rel="content" field="content"></div>



                 </div>
                 </div>


                  <module type="comments" template="mega">


                </div>
                <div class="span3">

                    <div class="bbox">
                        <div class="bbox-content">
                            <h5 class="orange">Categories</h5>
                            <hr>



                        </div>
                    </div>

                </div>
            </div>


           </div>

    </div>
 <?php include "footer.php"; ?>
