
               <? include "header.php"; ?>

           <? include "sidebar.php"; ?>


           <div id="main">
            <h2 class="title">Contacts</h2>
            <script type="text/javascript">
                $(document).ready(function(){
                  $(".contact_form form").validate(function(){
                    alert(1)
                  })
                });
            </script>

            <br /><br /><br /><br />


            <div class="contact_form">
             <h3 class="title nopadding">Send us a message</h3>
             <br /><br />
            <form method="post" action="">

              <span class="field"><input class="required" type="text" default="Name"  /></span>
              <span class="field"><input class="required-email" type="text" default="Email"  /></span>
              <span class="field"><input class="required" type="text" default="Phone"  /></span>
              <span class="area"><img src="<? print TEMPLATE_URL ?>img/formlogo.jpg" class="formlogo" /><textarea class="required" rows="" cols="" default="Message"></textarea></span>
              <input type="submit" value="" class="x" />
              <a href="#" class="btn2 submit right">Send</a>
            </form>


            </div>
            <div class="more_contacts">
                <h3 class="title nopadding">More contacts</h3>

                    <br /><br />
                   <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <img src="<? print TEMPLATE_URL ?>img/contact_ico1.jpg" alt="" />
                            </td>
                            <td>
                               Tilos, Inc.<br />
                                20822 Currier Road<br />
                                City of Industry, CA 91789<br />
                                USA<br />

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<? print TEMPLATE_URL ?>img/contact_ico2.jpg" alt="" />
                            </td>
                            <td>
Tilos Dealer Question/Support <br />
<a href="mailto:sale@tilos.com"><strong>sales@tilos.com</strong> </a>
<br /><br />
General Public Question/Support<br />
<a href="mailto:info@tilos.com"><strong>info@tilos.com</strong></a>


                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img style="top: -5px" src="<? print TEMPLATE_URL ?>img/contact_ico3.jpg" alt="" />
                            </td>
                            <td>

                                Tel: +1-909-348-0130<br />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+1-800-475-5703 Toll Free in US<br />
                                Fax:&nbsp;+1-909-348-0134
                            </td>
                        </tr>

                   </table>








            </div>



           </div>

               <? include "footer.php"; ?>
