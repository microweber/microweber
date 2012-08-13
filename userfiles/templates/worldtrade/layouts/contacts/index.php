<?php

/*

type: layout

name: contacts layout

description: contacts site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
 
 
?>
<script type="text/javascript">
$(function() {
 
  $(".button").click(function() {
	$eml = 	$('#contacts_form input[name="email"]').val();
		if($eml != ''){
		
		var dataString = ($('#contacts_form').serialize());
		//alert (dataString);return false;
		
		$.ajax({
      type: "POST",
      url: "<? print site_url('main/mailform_send'); ?>",
      data: dataString,
      success: function() {
        $('#contacts_form').html("<div id='message'></div>");
        $('#message').html("<h2>Contact Form Submitted!</h2>")
        .append("<p>We will be in touch soon.</p>")
        .hide()
        .fadeIn(1500, function() {
        //  $('#message').append("<img id='checkmark' src='images/check.png' />");
        });
      }
     });
		
		
		
		} else {
			
		alert("Please fill the form")	
		}
		
		
		
		
    return false;
	});
});
</script>

<div id="middle">
  <table border="0" cellspacing="20" cellpadding="10">
  <tr valign="top">
    <td><form method="post" action="#" id="contacts_form" class="left_col left w500">
    <p class="marginB50">За да се свържете с нас попълнете формата и ни изпратете е-мейл, ние ще се свържем с вас възможно най-скоро! Благодарим Ви!</p>
    <label>Име</label>
    <div class="rounded input left">
      <div class="in1">
        <div class="in2">
          <input type="text" name="name">
        </div>
      </div>
    </div>
    <div class="clener h16"></div>
    <label>Телефон</label>
    <div class="rounded input left">
      <div class="in1">
        <div class="in2">
          <input type="text" name="phone">
        </div>
      </div>
    </div>
    <div class="clener h16"></div>
    <label>Емайл</label>
    <div class="rounded input left">
      <div class="in1">
        <div class="in2">
          <input type="text" name="email">
        </div>
      </div>
    </div>
    <div class="clener h16"></div>
    <label>Съобщение</label>
    <textarea class="left" name="message" rows="" cols=""></textarea>
    <div class="clener h16"></div>
    <div class="rounded white_btn right marginR54">
      <div class="in1">
        <input type="button"   value="Изпрати" class="in2 button">
      </div>
    </div>
  </form></td>
    <td><editable  rel="page" field="content_body">
    <h1 class="font_size_18 pink_color">За контакти с нас</h1>
    <br />
    <div class="right_col right w300">
      <p> <strong>Адрес:</strong> София, България<br />
        кв. Дианабат бл. 14, ет 1, ап 1 и 2<br />
        <br />
        <strong>Телефон:</strong> 02 / 495 14 24<br />
        Мобилен:  0877 905 879  Име за връзка Длъжност<br />
        <br />
        <strong>Емейл:</strong> Info at worldtrade.bg<br />
        <strong>Уеб Сайт:</strong> www.worldtrade.bg<br />
        <br />
        <a href="#"><img src="<? print TEMPLATE_URL ?>images/google-maps.jpg" alt="" /></a><br />
        <br />
        <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/news/facebook.jpg" /></a> </p>
    </div>
  </editable></td>
  </tr>
</table>

  
  <div class="clener"></div>
</div>
<div class="pattern_line all_width"></div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
