<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="main">

		<div id="container">
			<div id="content" role="main">

			

				<div id="greyC">
				<div class="wrapper">
				<div id="post-108" class="post-108 page type-page status-publish hentry">
                <br><br>
                   <div class="b">
                   <div class="bt">&nbsp;</div>
                   <div class="bm">
						<h1 class="entry-title"><? print $page['content_title'] ?></h1>


					<div class="entry-content manitext">
					<editable rel="page" field="content_body"></editable>
						
                                                            
					</div><!-- .entry-content -->



                   </div>
                   <div class="bb">&nbsp;</div>
                 </div>
                 <br><br>
				</div><!-- #post-## -->
				</div>
				</div>

				




			</div><!-- #content -->
		</div><!-- #container -->


	</div>

 
<? include   TEMPLATE_DIR.  "footer.php"; ?>
