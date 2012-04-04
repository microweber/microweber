  <?
  
   
   $user = $u = get_user(url_param('username'));
 
   ?>
   
   
    <? // p( $user); ?>
<div class="page_tit"><? print user_name($u['id']); ?></div>
<div class="body_part_inner">
  <a class="jobseaker_pic" style="background-image:url('<? print user_picture($u['id'], 200); ?>')" href="<? print user_picture($u['id'], 'original'); ?>"   target="_blank" ></a>
  <div class="jobseaker_desc"><strong> Josh Mayfid</strong><br />
    <br />
    <p>Hi, I'm Dr. Josh Mayfid. <br />
      Antigo is my home and where I grew up as an outdoor enthusiast. Over the years, I passed on my outdoor skills to kids in our community and knew that I wanted to continue working with children in the dental profession. </p>
    <p>I obtained my undergraduate degree in Kinesiology at the University of Wisconsin - Madison. I went on to dental school at the University of Minnesota where I completed my pediatric residency.&rdquo;</p>
  </div>
  <div class="jobseaker_tit">Job Seekers 	 	Information</div>
      <? include (TEMPLATE_DIR. "layouts".DS."companies".DS."profile_box.php"); ?>
  <div class="contactme_tit">Contact Me</div>
  <div class="jobseaker_contact_box">
    <div class="jobseaker_contact_left">
      <div class="jobseaker_pic"><img src="images/job_seaker1.jpg" alt="jobseaker" /></div>
      <div class="jobseaker_name_arr">Josh Mayfid</div>
    </div>
    <div class="jobseaker_contact_rt">
      <input type="text" class="jobseaker_formtext" value="Name" />
      <input type="text" class="jobseaker_formtext" value="E-mal" />
      <input type="text" class="jobseaker_formtext" value="Phone" />
      <textarea name="" cols="" rows="" class="jobseaker_mesg"></textarea>
      <div class="jobseaker_sendbut">
        <input type="image" src="images/jobseaker_send_but.jpg" />
      </div>
    </div>
  </div>
</div>
