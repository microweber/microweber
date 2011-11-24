<?php include "header.php"; ?>


<div class="wrap">
    <div id="main_content">
      <div id="user_sidebar">
        <div id="user_image">
            <img src="img/demo/user.jpg" alt="" />
            <a href="#">Change profile Image</a>

        </div>
        <h3 id="user_image_name">Syzana Love</h3>

        <ul id="user_side_nav">
          <li id="user_side_nav_msg"><a href="#">Messages</a></li>
          <li id="user_side_nav_pics"><a href="#">My Pictures</a></li>
          <li id="user_side_nav_videos"><a href="#">My Videos  (02)</a></li>
          <li id="user_side_nav_friends"><a href="#">Find New Friends</a></li>
          <li id="user_side_nav_edit_profile"><a href="#">Edit my profile</a></li>
        </ul>

        <h3 class="user_sidebar_title">Information about Me</h3>

<div class="richtext">
  <p>
    Data of birthday<br />
    <strong>06/01/1993</strong>
  </p>

  <p>
      Country<br />
      <strong>USA</strong>
  </p>
</div>

<h3 class="user_sidebar_title">About me</h3>
<div class="richtext">
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>

<h3 class="user_sidebar_title">My Friends</h3>

<div class="friends_side_box">

  <a href="#" class="mw_blue_link">326 friends</a>

  <a href="#" class="mw_btn_s right"><span>See All</span></a>

 <div class="c">&nbsp;</div>
  <ul class="user_friends_list">
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>John Smith</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Abby Alene</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Alex John</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>John Smith</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Abby Alene</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Alex John</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>John Smith</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Abby Alene</strong>
        </a>
    </li>
    <li>
        <a href="#">
            <span style="background-image: url(img/demo/userbox.jpg)"></span>
            <strong>Alex John</strong>
        </a>
    </li>
  </ul>


</div>


      </div><!-- /#user_sidebar -->


      <div id="sidebar">
          sidebar
      </div>

      <div id="wall">

        <form method="post" action="#" id="status_update">
            <input type="text" value="What are you thinking?" onfocus="this.value=='What are you thinking?'?this.value='':''" onblur="this.value==''?this.value='What are you thinking?':''" />
            <input type="submit" class="mw_hidden" />
            <a href="#" class="submit">Say</a>
        </form>

        

      </div><!-- /#wall -->





    </div><!-- /#main_content -->
</div><!-- /#wrap -->




<?php include "footer.php"; ?>