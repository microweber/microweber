<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="home_center_grad2">
  <div class="home_center_mid container_holder">
    <editable  rel="page" field="content_body">
      <div class="container">
        <div class="row">
          <div class="col" style="width:448px;"> <img width="448"  height="322" src="<? print TEMPLATE_URL ?>img/ppl.png" /> </div>
          <div class="col" style="width:520px;">
            <div class="row">
              <div class="col" style="width:100px;">
              
              
              <br />
<br />
<br />
<br />

<span style="color:#707070; font-weight:bold; font-size:18px;">              The new</span>   <br />
<span style="color:#707070;font-size:14px;">book for 11+</span>   <br />
               <br />
<img    src="<? print TEMPLATE_URL ?>img/arr1.png" /> 
<br />
<br />
<br />

              <span style="color:#707070;font-size:12px;">Check out the new books. 
</span>   </div>
              <div class="col" style="width:300px;"> <img    src="<? print TEMPLATE_URL ?>img/book1.png" /> </div>
              <div class="col" style="width:100px;"> <img    src="<? print TEMPLATE_URL ?>img/book2.png" /> <br />
                <br />
                <img    src="<? print TEMPLATE_URL ?>img/book3.png" /> </div>
            </div>
          </div>
        </div>
      </div>
    </editable>
  </div>
</div>
<div class="wide_stripe"></div>
<div class="home_center_colored">
  <div class="home_center_mid container_holder">
    <editable  rel="page" field="custom_field_content_second">
      <div class="container">
        <div class="row">
          <div class="col" style="width:448px;"> <img  src="<? print TEMPLATE_URL ?>img/book_big.png" /> </div>
          <div class="col" style="width:520px;">
            <h1>Yes! The best book to educate your child is coming</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
            <div class="container">
              <table border="0" >
                <tr valign="top" >
                  <td style="padding:10px;" ><img  src="<? print TEMPLATE_URL ?>img/check1.png" align="left" /></td>
                  <td style="padding:10px;"><span class="big_text">Lorem Ipsum has been the industry's standard du</span> <br />
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. </td>
                </tr>
                <tr valign="top" >
                  <td style="padding:10px;" ><img  src="<? print TEMPLATE_URL ?>img/check2.png" align="left" /></td>
                  <td style="padding:10px;"><span class="big_text">Lorem Ipsum has been the industry's standard du</span> <br />
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. </td>
                </tr>
                <tr valign="top" >
                  <td style="padding:10px;" ><img  src="<? print TEMPLATE_URL ?>img/check3.png" align="left" /></td>
                  <td style="padding:10px;"><span class="big_text">Lorem Ipsum has been the industry's standard du</span> <br />
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </editable>
  </div>
</div>
<div class="home_center_wide">
  <div class="home_center_mid">
    <editable  rel="page" field="custom_field_content_third">
      <div class="container">
      
        <div class="row">
          <div class="col" style="width:630px;"> 
            <h2>What the people are saying</h2>
          <microweber module="posts/list" tn_size="80" file="mics/posts_list_1" />
          
          </div>
          <div class="col" style="width:330px; margin-left:10px;" >
            <h2>From our blog</h2>
              <microweber module="posts/list" tn_size="80" file="mics/posts_list_2" />
          </div>
        </div>
      </div>
    </editable>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
