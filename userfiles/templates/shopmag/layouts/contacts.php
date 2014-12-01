<?php

/*

  type: layout
  content_type: static
  name: Contact us
  position: 5
  description: Contacts
  tag: about, contacts

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="mw-wrapper content-holder contact-us-page">
    <div class="edit richtext" rel="page" field="content">
      <div class="item-box pad">
          <h1 align="center">Contact us</h1>
          <p align="center">
              Nam vel ex gravida orci rutrum varius et sit amet neque. Duis eget tincidunt dolor, vel auctor neque. Praesent odio risus, sagittis fermentum quam et, ultricies vulputate enim.
              Sed congue placerat felis, quis viverra eros efficitur et. Pellentesque aliquam sed mauris vel sodales.
          </p>
          </div>
          <br>
           <module type="google_maps" />
          <br>
          <div class="item-box pad"><div class="mw-row">
              <div class="mw-col" style="width: 48%">
                <div class="mw-col-container">
                  <module type="contact_form">
                </div>
              </div>
              <div class="mw-col" style="width: 4%">
                <div class="mw-col-container">

                </div>
              </div>
              <div class="mw-col" style="width: 48%">
                <div class="mw-col-container">
                    <h2>Address</h2>
                  <ul class="mw-list">
                    <li><span class="mw-icon-map"></span>Sofia 1700, Bulgaria, My place #10 str. , bl. B, fl. 3</li>
                    <li><span class="mw-icon-app-telephone"></span>+1 234-567-890</li>
                  </ul>

                  <hr>
                 <h2>Follow Us</h2>
                  <ul class="mw-list">
                      <li>
                        <a href="https://facebook.com/Microweber"><span class="mw-icon-facebook"></span>https://facebook.com/Microweber</a>
                      </li>
                      <li>
                        <a href="https://twitter.com/Microweber"><span class="mw-icon-twitter"></span>https://twitter.com/Microweber</a>
                      </li>
                      <li>
                        <a href="https://plus.google.com/+Microweber/"><span class="mw-icon-googleplus"></span>https://plus.google.com/+Microweber/</a>
                      </li>
                  </ul>
                </div>
              </div>
          </div></div>

    </div>
</div>

<?php include TEMPLATE_DIR. "footer.php"; ?>
