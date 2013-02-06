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
            <div class="post-meta">Posted by: <a href="#">owltemplates </a> | Posted in: <a href="#">template,</a><a href="#">premium</a> </div>
            <p class="element">Lorem ipsum dolor sit amet, consectetueradipiscing elied diam nonummy nibh euisod tincidunt ut laoreet dolore magna aliquam erat volutpatorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Donec quam felis, ultricies nec, pellentesque pretium quis, sem. Nulla consequat massa quis enim. Donec pe justo fringilla vel, aliquet nec vulputate eget, arcu enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felisa penelore mollis pretium. Integer tincidunt vamus elementum semper nisi. </p>
            <p class="p0 element">Donec quam felis, ultricies nec, pellentesque pretium quis, sem. Nulla consequat massa quis enim. Donec pe justo fringilla vel, aliquet nec vulputate eget, arcu enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felisa penelore mollis pretium. Integer tincidunt vamus elementum semper nisi. Lorem ipsum dolor sit amet, consectetueradipiscing elied diam nonummy nibh euisod tincidunt ut laoreet dolore magna aliquam erat volutpatorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. </p>
          </div>
          <div class="blog-post-footer">
            <div class="btn-group"><a class="btn" href="#"><i class="team-social-twitter"></i></a> <a class="btn" href="#"><i class="team-social-facebook"></i></a> <a class="btn" href="#"><i class="team-social-skype"></i></a> <a class="btn" href="#"><i class="team-social-youtube"></i></a></div>
          </div>
        </div>
        <div class="blog-comments">
          <module type="comments" />
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
        <? include TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
