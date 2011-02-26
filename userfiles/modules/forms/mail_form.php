      <script type="text/javascript">
                $(document).ready(function(){
                  $(".contact_form form").validate(function(){
                    alert(1)
                  })
                });
            </script>
 
 
 <div class="contact_form">
             <h3 class="title nopadding">Send us a message</h3>
             <br /><br />
            <form method="post" action="">
            <input  type="hidden"   name="to_emails" value="<? print base64_encode($params['email']); ?>"  />
             
 
              <span class="field"><input class="required" type="text" default="Name" name="from_name"  /></span>
              <span class="field"><input class="required-email" type="text" default="Email"  name="from_email"   /></span>
              <span class="field"><input class="required" type="text" default="Phone"  /></span>
              <span class="area"><img src="<? print TEMPLATE_URL ?>img/formlogo.jpg" class="formlogo" /><textarea class="required" rows="" cols=""  name="message" default="Message"></textarea></span>
              <input type="submit" value="" class="x" />
              <a href="#" class="btn2 submit right">Send</a>
              
              
              
            </form>