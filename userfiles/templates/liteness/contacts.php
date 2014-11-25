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
      						<h3 class="border-title">Address</h3>
                            <div class="contacts-icons">
        					  <p>
                                  <span class="symbol">&#xe041;</span>Sofia 1700, Bulgaria, My place #10 str. , bl. B, fl. 3
                              </p>
                              <p>
                                  <span class="glyphicon glyphicon-phone"></span>+1 234-567-890
                              </p>
                            </div>
      					</div>
                      <h3 class="border-title">Follow Us</h3>
                      <div class="contacts-icons">
                        <p>
                          <span class="symbol">&#xe027;</span>
                          <a href="https://facebook.com/Microweber">https://facebook.com/Microweber</a>
                        </p>
                        <p>
                          <span class="symbol">&#xe086;</span>
                          <a href="https://facebook.com/Microweber">https://twitter.com/Microweber</a>
                        </p>
                        <p>
                          <span class="symbol">&#xe039;</span>
                          <a href="https://plus.google.com/+Microweber/">https://plus.google.com/+Microweber/</a>
                        </p>
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
