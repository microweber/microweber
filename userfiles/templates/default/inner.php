<?php

/*

type: layout
content_type: post
name: Post inner layout

description: Post inner layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container"> 
    <!--=========== Blog ===========-->
    <div class="row"> 
      <!-------------- Blog post -------------->
      <div class="span8"> 
        <!-- blog 1 -->
        <div class="blog-post">
          <div class="blog-post-header">
            <h2  class="edit"  rel="content"  field="title">Your title goes here</h2>
            <a class="btn blog-fright" href="#"><i> 14 </i></a> </div>
          <div class="blog-post-body edit"  rel="content"  field="content"> <img src="{TEMPLATE_URL}img/blog-1.jpg" alt="" class="img-circle">
            <div class="post-meta">Posted by: <a href="#">owltemplates </a> | Posted in: <a href="#">template,</a> <a href="#">wordpress,</a> <a href="#">premium</a> </div>
            <p>Lorem ipsum dolor sit amet, consectetueradipiscing elied diam nonummy nibh euisod tincidunt ut laoreet dolore magna aliquam erat volutpatorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Donec quam felis, ultricies nec, pellentesque pretium quis, sem. Nulla consequat massa quis enim. Donec pe justo fringilla vel, aliquet nec vulputate eget, arcu enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felisa penelore mollis pretium. Integer tincidunt vamus elementum semper nisi. </p>
            <p class="p0">Donec quam felis, ultricies nec, pellentesque pretium quis, sem. Nulla consequat massa quis enim. Donec pe justo fringilla vel, aliquet nec vulputate eget, arcu enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felisa penelore mollis pretium. Integer tincidunt vamus elementum semper nisi. Lorem ipsum dolor sit amet, consectetueradipiscing elied diam nonummy nibh euisod tincidunt ut laoreet dolore magna aliquam erat volutpatorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. </p>
          </div>
          <div class="blog-post-footer">
            <div class="btn-group"><a class="btn" href="#"><i class="team-social-twitter"></i></a> <a class="btn" href="#"><i class="team-social-facebook"></i></a> <a class="btn" href="#"><i class="team-social-skype"></i></a> <a class="btn" href="#"><i class="team-social-youtube"></i></a></div>
          </div>
        </div>
        <div class="blog-comments">
          <h2>25 Comments</h2>
          <div class="parent">
            <figure class="img-circle blog-fleft"><img src="{TEMPLATE_URL}img/mark.jpg" alt="" class="img-circle"></figure>
            <h2><a href="#">Mark Abucayon</a></h2>
            <span>October 18, 2012 at 5:02pm</span> <a class="reply" href="#">Reply</a>
            <p> Donec ut mauris vel risus rutrum commodo. Duis sed massa id urna elementum varius. Fusce nibh sapien, porttitor sed euismod in, aliquet eget lorem. Curabitur scelerisque mauris quis diam gravida eu placerat ligula scelerisque. In vitae sem nec massa imperdiet condimentum. </p>
          </div>
          <div class="parent child">
            <figure class="img-circle blog-fleft"><img src="{TEMPLATE_URL}img/jade.jpg" alt="" class="img-circle"></figure>
            <div class="parent-sub-img"></div>
            <h2><a href="#">Jade Thompson</a></h2>
            <span>October 18, 2012 at 5:02pm</span> <a class="reply" href="#">Reply</a>
            <p> Donec ut mauris vel risus rutrum commodo. Duis sed massa id urna elementum varius. Fusce nibh sapien, porttitor sed euismod in, aliquet eget lorem. Curabitur scelerisque mauris quis diam gravida eu placerat ligula scelerisque. In vitae sem nec massa imperdiet condimentum. </p>
          </div>
          <div class="parent subchild">
            <figure class="img-circle blog-fleft"><img src="{TEMPLATE_URL}img/mark.jpg" alt="" class="img-circle"></figure>
            <div class="parent-sub-img"></div>
            <h2><a href="#">Mark Abucayon</a></h2>
            <span>October 18, 2012 at 5:02pm</span>
            <p> Donec ut mauris vel risus rutrum commodo. Duis sed massa id urna elementum varius. Fusce nibh sapien, porttitor sed euismod in, aliquet eget lorem. Curabitur scelerisque mauris quis diam gravida. </p>
          </div>
          <div class="parent">
            <figure class="img-circle blog-fleft"><img src="{TEMPLATE_URL}img/jade.jpg" alt="" class="img-circle"></figure>
            <h2><a href="#">Jade Thompson</a></h2>
            <span>October 18, 2012 at 5:02pm</span> <a class="reply" href="#">Reply</a>
            <p> Donec ut mauris vel risus rutrum commodo. Duis sed massa id urna elementum varius. Fusce nibh sapien, porttitor sed euismod in, aliquet eget lorem. Curabitur scelerisque mauris quis diam gravida eu placerat ligula scelerisque. In vitae sem nec massa imperdiet condimentum. </p>
          </div>
        </div>
        <form id="form1" class="contact-form">
          <div class="success">Contact form submitted!<strong><br>
            We will be in touch soon.</strong> </div>
          <fieldset class="thumbnails thumbnails_2">
            <label class="name span4">
              <input type="text" value="Name:">
              <span class="error">*This is not a valid name.</span> <span class="empty">*Please enter Name</span> </label>
            <label class="email span4">
              <input type="email" value="E-mail:">
              <span class="error">*This is not a valid email address.</span> <span class="empty">*Please enter Email</span> </label>
            <label class="message span8">
              <textarea class="message1">Message:</textarea>
              <span class="error">*The message is too short.</span> <span class="empty">*Please enter Some Text</span> </label>
          </fieldset>
          <div class="pull-right"> <a href="#" class="btn btn-warning" data-type="reset">Clear</a> <a href="#" class="btn btn-success" data-type="submit">Send Comment</a> </div>
        </form>
      </div>
      <!------------ Sidebar -------------->
      <div class="span4"> 
        <!-- Blog Categories -->
        <h2 class="indent-2">Blog Categories</h2>
        <ul class="list">
          <li><a href="#">Quisque diam lorem sectetuer adipiscing</a></li>
          <li><a href="#">Interdum vitae, adipiscing dapibus ac</a></li>
          <li><a href="#">Scelerisque ipsum auctor vitae, pede</a></li>
          <li><a href="#">Donec eget iaculis lacinia non erat</a></li>
          <li><a href="#">Lacinia dictum elementum velit fermentum</a></li>
          <li><a href="#">Donec in velit vel ipsum auctor pulvinar</a></li>
        </ul>
        <!-- Tabs -->
        <div class="sidebar-tabs">
          <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#text" data-toggle="tab">Text</a></li>
            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">other <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#testimonial1" data-toggle="tab">Testimonial 1</a></li>
                <li><a href="#testimonial2" data-toggle="tab">Testimonial 2</a></li>
              </ul>
            </li>
            <li><a href="#recent" data-toggle="tab">Post</a></li>
            <li><a href="#list" data-toggle="tab">List</a></li>
          </ul>
          <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="text">
              <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
            </div>
            <div class="tab-pane fade" id="testimonial1">
              <div class="extra-wrap">
                <blockquote><em>Morbi interdum fermentum nulla, ut imperdiet felis tempus in. Ut lacinia consectetur nisl et iaculis. Duis fringilla dui  at leo.</em><em>Integer pharetra lobortis nisl vitae aliquam. Lorem ipsum dolor sit amet, consectetur elit sapien accumsan quis ...”</em></blockquote>
                <span>- Patrick Pool<br>
                client</span> </div>
            </div>
            <div class="tab-pane fade" id="testimonial2">
              <div class="extra-wrap">
                <blockquote><em>Morbi interdum fermentum nulla, ut imperdiet felis tempus in. Ut lacinia consectetur nisl et iaculis. Duis fringilla dui  at leo.</em><em>Integer pharetra lobortis nisl vitae aliquam. Lorem ipsum dolor sit amet, consectetur elit sapien accumsan quis ...”</em></blockquote>
                <span>- Patrick Pool<br>
                client</span> </div>
            </div>
            <div class="tab-pane fade" id="recent">
              <ul class="footer-list-news">
                <li>
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img8.jpg" alt="" class="img-circle"></figure>
                  <div class="extra-wrap">
                    <p><a href="#">Interdum vitae dapibus volutpat.</a></p>
                    <span>12 October 2012</span> </div>
                </li>
                <li>
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img9.jpg" alt="" class="img-circle"></figure>
                  <div class="extra-wrap">
                    <p><a href="#">Interdum vitae dapibus volutpat.</a></p>
                    <span>12 October 2012</span> </div>
                </li>
                <li>
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img10.jpg" alt="" class="img-circle"></figure>
                  <div class="extra-wrap">
                    <p><a href="#">Interdum vitae dapibus volutpat.</a></p>
                    <span>12 October 2012</span> </div>
                </li>
                <li class="p0">
                  <figure class="img-circle"><img src="{TEMPLATE_URL}img/page1-img11.jpg" alt="" class="img-circle"></figure>
                  <div class="extra-wrap">
                    <p><a href="#">Interdum vitae dapibus volutpat.</a></p>
                    <span>12 October 2012</span> </div>
                </li>
              </ul>
            </div>
            <div class="tab-pane fade" id="list">
              <ul class="list">
                <li><a href="#">Quisque diam lorem sectetuer adipiscing</a></li>
                <li><a href="#">Interdum vitae, adipiscing dapibus ac</a></li>
                <li><a href="#">Scelerisque ipsum auctor vitae, pede</a></li>
                <li><a href="#">Donec eget iaculis lacinia non erat</a></li>
                <li><a href="#">Lacinia dictum elementum velit fermentum</a></li>
                <li><a href="#">Donec in velit vel ipsum auctor pulvinar</a></li>
              </ul>
            </div>
          </div>
        </div>
        <!-- text widget -->
        <div class="text-widget">
          <h2>Text Widget / Business Services</h2>
          <small class="p2">Fusce euismod consequat ante. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque sed dolor. Aliquam congue fermentum nisl. Mauris accumsan nulla </small>
          <p class="p1">Lorem ipsum dolor sit amet, consect elied diam nonummy nibh euisod tincidunt ut laoreet dolore magna aliquam
            erat volutpatorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo</p>
          <a href="#" class="btn">Business Services</a> </div>
        <!-- Image -->
        <h2>Image Categories</h2>
        <ul class="sidebar-image-list">
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img8.jpg"></figure>
          </li>
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img9.jpg"></figure>
          </li>
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img10.jpg"></figure>
          </li>
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img11.jpg"></figure>
          </li>
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img8.jpg"></figure>
          </li>
          <li>
            <figure class="img-circle"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/page1-img9.jpg"></figure>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
<module data-type="comments" id="comments_posts" data-content-id="<? print POST_ID ?>"  />
<? include   TEMPLATE_DIR.  "footer.php"; ?>
