<?  include "header.php"  ?>


            <div class="wrap" id="about">
                <div class="img">

                </div>
                <div class="author-content">
                    <h2 class="title">Контакти</h2>
                    <p><big style="font-size: 16px;">Можете да ни изпратите e-mail или да се свържете с нас на посочените телефони!</big></p>
                    <p><strong>Можете да се свържете с нас от понеделник до петък на посочените телефони:</strong></p>
                    <p style="line-height: 20px">
                        Адрес на офиса: Пимен Зографски 14<br />
                        Офис Телефон: 02 495 14 24<br />
                        Димитър Божанов 0898 571 719<br />
                        Мас Медиа Публишинг ООД<br />
                    </p>
                   <br /><br />

                   <h2 class="title">Напишете ни E-mail</h2>

                   <form method="post" action="#" id="contacts-form">
                      <span class="field">
                          <input id="cname" title="Вашето Име" class="required" type="text" value="Вашето Име" onfocus="this.value=='Вашето Име'?this.value='':''" onblur="this.value==''?this.value='Вашето Име':''" />
                      </span>
                      <span class="field">
                          <input id="cmail" title="Вашият E-mail" class="required-email" type="text" value="Вашият E-mail" onfocus="this.value=='Вашият E-mail'?this.value='':''" onblur="this.value==''?this.value='Вашият E-mail':''" />
                      </span>
                      <span class="field">
                          <input id="cphone" title="Вашият Телефон" class="required" type="text" value="Вашият Телефон" onfocus="this.value=='Вашият Телефон'?this.value='':''" onblur="this.value==''?this.value='Вашият Телефон':''" />
                      </span>
                      <span class="fieldarea">
                          <textarea id="cmsg" cols="" rows="" title="Съобщение" class="required" onfocus="this.value=='Съобщение'?this.value='':''" onblur="this.value==''?this.value='Съобщение':''" >Съобщение</textarea>
                      </span>
                      <input type="submit" class="type-submit" value="" />
                      <div id="contacts-overlay">&nbsp;</div>
                   </form>

                </div>
            </div>

<? include "footer.php" ?>