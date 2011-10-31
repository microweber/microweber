     <form method="post" action="#" id="contacts-form">
                      <span class="field">
                          <input id="cname" title="Вашето Име" class="required" type="text" value="Вашето Име" onfocus="this.value=='Вашето Име'?this.value='':''" onblur="this.value==''?this.value='Вашето Име':''" />
                      </span>
                      <span class="field">
                          <input id="cmail" title="Вашият E-mail" class="required-email" type="text" value="Вашият E-mail" onfocus="this.value=='Вашият E-mail'?this.value='':''" onblur="this.value==''?this.value='Вашият E-mail':''" />
                      </span>
                      <span class="field">
                          <input id="cphone" title="Вашият Телефон"   type="text" value="Вашият Телефон" onfocus="this.value=='Вашият Телефон'?this.value='':''" onblur="this.value==''?this.value='Вашият Телефон':''" />
                      </span>
                      <span class="fieldarea">
                          <textarea id="cmsg" cols="" rows="" title="Съобщение" class="required" onfocus="this.value=='Съобщение'?this.value='':''" onblur="this.value==''?this.value='Съобщение':''" >Съобщение</textarea>
                      </span>
                      <input type="submit" class="type-submit" value="" />
                      <div id="contacts-overlay">&nbsp;</div>
                   </form>