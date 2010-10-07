<form  method="post" action="#" id="contact_form">

                <div class="heading"><h1>Форма за контакти</h1></div>

                <div class="contactwrap">
                  <label>Име: *</label>
                  <div class="contact_input">
                    <input type="text" id="contacts_name" class="required" />
                  </div>
                  <div class="clear height-10px"></div>
                </div>
                <div class="contactwrap">
                  <label>Фирма/Организация:</label>
                  <div class="contact_input">
                    <input type="text" id="contacts_firm" />
                  </div>
                  <div class="clear height-10px"></div>
                </div>
                <div class="c">&nbsp;</div>
                <div class="contactwrap">
                  <label>е-mail: *</label>
                  <div class="contact_input">
                    <input type="text" id="contacts_email" class="required-email" />
                  </div>
                </div>
                <div class="contactwrap">
                  <label>Телефон: *</label>
                  <div class="contact_input">
                    <input type="text" id="contacts_phone" class="required" />
                  </div>
                  <div class="clear height-10px"></div>
                </div>
                <div class="clear height-10px"></div>
                <div class="contactwrap">
                 <label>Относно:</label>
                <div class="contact_input">
                  <select id="otnosno">
                    <option value="Изберете тема от списъка">Изберете тема от списъка</option>




  <option value="Културен туризъм">Културен туризъм</option>
  <option value="Конгресен туризъм">Конгресен туризъм</option>
  <option value="Почивки">Почивки</option>
  <option value="Екскурзии">Екскурзии</option>
  <option value="Хотелски резервации">Хотелски резервации</option>
  <option value="Самолетни билети">Самолетни билети</option>
  <option value="Друго">Друго</option>
                  </select>
                </div>
                <div class="clear height-10px"></div>
                </div>
                <div class="c"></div>
                <div class="contactwrap">
                <label>Съобщение: *</label>
                <div class="contact_textarea">
                  <textarea cols="" rows="" id="contacts_message" class="required"></textarea>
                </div>
                </div>
                <div class="clear height-10px"></div>
                <input type="submit" value="Изпрати" class="search" />
            </form>


            <script type="text/javascript">
            $(document).ready(function(){
                $("#contact_form").validate(function(){
                    $("#contact_form").disable();

                    var contacts_name = $("#contacts_name").val();
                    var contacts_firm = $("#contacts_firm").val();
                    var contacts_email = $("#contacts_email").val();
                    var contacts_phone = $("#contacts_phone").val();
                    var otnosno = $("#otnosno").val();
                    var contacts_message = $("#contacts_message").val();

                    var options = {
                        contacts_name:contacts_name,
                        contacts_firm:contacts_firm,
                        contacts_email:contacts_email,
                        contacts_phone:contacts_phone,
                        otnosno:otnosno,
                        contacts_message:contacts_message

                    }

                    $.post(template_url+'send_contact.php', options, function(data){
                      //alert(data);
                        Modal.overlay();
                        Modal.box('<h2 class="ajax_success_message">Съобщението е изпратено успешно.</h2>', 500, 150);
                       $("#contact_form").enable();
                    });
                });
            });
            </script>