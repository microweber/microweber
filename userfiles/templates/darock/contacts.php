<?php

/*

type: layout
content_type: static
name: Contact Us

description: Contact us layout
position: 7
*/


?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div id="content">
	<div class="container">
	<div class="box-container">

		<h2 class="edit element" field="title" rel="content" align="center">Page Title</h2>
		<div class="edit" field="content" rel="content">



            <p><br></p>

                <module type="google_maps">


            <p align="center" class="element">
                Contact us and share with us you thoughts.
                We will respond as soon as we can.
                Thank you!
            </p>

            <p><br></p>

            <div class="mw-row contacts-block">
                <div class="mw-col" style="width: 50%;">
                     <div class="mw-col-container">
                        <module type="contact_form" id="contact-form" />
                     </div>
                </div>
                <div class="mw-col" style="width: 50%;">
                     <div class="mw-col-container">
                        <div class="well contacts-info">
                          <div class="edit" field="content_body" rel="content">
                            <h3>Morbi eu mollis erat</h3>
                            <hr>
      						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus augue tellus, aliquet sed sagittis in, molestie sagittis ante. Quisque venenatis lorem sit amet placerat posuere.</p>
                            <hr>
                            <module type="social_links">
      					  </div>
                      </div>
                  </div>
              </div>
            </div>
            <p class="element"><br></p>
		</div>
	</div>
	</div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
