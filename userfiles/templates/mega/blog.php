<?php

/*

  type: layout
  content_type: static
  name: Blog
  description: Blog layout

*/

?>

 <?php include "header.php"; ?>
    <div class="container">

           <div class="main">

            <div class="row">
                <div class="span9">

                 <module type="posts" template="mega">


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
