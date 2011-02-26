
<div class="wrap">

<div id="main_content">

<h2>Contacts</h2>
<br />

<table cellpadding="0" cellspacing="0" id="ct">
  <tr><td>For all your inquiries regarding support, please send request to.</td>              <td><a class="mw_blue_link" href="mailto:support@skidekids.com">support@skidekids.com</a></td></tr>

  <tr><td>For information about  skidekids Network, send request to.</td>                     <td><a class="mw_blue_link" href="mailto:info@skidekids.com">info@skidekids.com</a></td></tr>

  <tr><td>For information regarding  Internet  Security, and to report abuse.</td>            <td><a class="mw_blue_link" href="mailto:security@skidekids.com">security@skidekids.com</a></td></tr>

  <tr><td>For information regarding your Account / Membership /Payments. </td>          <td><a class="mw_blue_link" href="mailto:accounts@skidekids.com">accounts@skidekids.com</a></td></tr>

  <tr><td>Please send all Media Request to.</td>                                                        <td><a class="mw_blue_link" href="mailto:media@skidekids.com">media@skidekids.com</a></td></tr>

  <tr><td>To Advertise your  Business on skidekids, please send inquiry to .</td>          <td><a class="mw_blue_link" href="mailto:advertising@skidekids.com">advertising@skidekids.com</a></td></tr>

  <tr><td>For employment opportunities.</td>                                                              <td><a class="mw_blue_link" href="mailto:employment@skidekids.com">employment@skidekids.com</a></td></tr>
</table>




<br /><br />


<style>
    #contact-form label{
      float: none;
      width: auto;
    }
    #ct td{
      padding: 10px;
      border-bottom: 1px solid #DFDFDF;
    }
    #ct{
      border-top: 1px solid #DFDFDF;
    }


</style>
<script type="text/javascript">
                  $(document).ready(function(){
                      $("#contact-form").validate(function(){
                        $("#contact-form").xDisable();
                        var name = $("#name").val();
                        var email = $("#email").val();
                        var message = $("#message").val();

                        var options = {
                          name:name,
                          email:email,
                          message:message
                        }

                        $.post(template_url + "contact_form.php", options, function(data){
                            mw.box.alert("<strong>Your message has been sent.</strong>");
                            $("#contact-form").xEnable();
                        });

                      });
                  });
              </script>
      <form method="post" action="#" id="contact-form" class="form relative">
        <div class="item">
          <label>Name: *</label>
          <span class="field">
          <input type="text" id="name" class="required" style="width: 388px;" />
          </span> </div>
        <div class="item">
          <label>E-mail: *</label>
          <span class="field">
          <input type="text" id="email" class="required-email" style="width: 388px;" />
          </span> </div>
        <div class="item">
          <label>Message: *</label>
          <span class="field">
          <textarea id="message" rows="" cols="" class="required" style="width: 388px;height: 80px;"></textarea>
          </span> </div>
        <input type="submit" value="" class="hidden" />
        <a class="mw_btn_x submit" href="javascript:;" ><span>Send</span></a>
      </form>

</div>

</div>