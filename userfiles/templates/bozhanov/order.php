<?  include "header.php"  ?>


            <div class="wrap" id="about">
                <div class="img">
                   <img alt="" style="float: left; margin-left: -20px;" src="img/sidebookt.jpg">
                </div>
                <div class="author-content" style="width: 680px;margin-left: 30px;">

                   <h2 class="title">Форма за поръчки</h2>
                   <br />

                   <form method="post" action="#" id="order-form">
                      <span class="field">
                          <input id="cname" title="Вашето Име" class="required" type="text" value="Вашето Име" onfocus="this.value=='Вашето Име'?this.value='':''" onblur="this.value==''?this.value='Вашето Име':''" />
                      </span>
                      <span class="field">
                          <input id="cmail" title="Вашият Е-майл" class="required-email" type="text" value="Вашият Е-майл" onfocus="this.value=='Вашият Е-майл'?this.value='':''" onblur="this.value==''?this.value='Вашият Е-майл':''" />
                      </span>
                      <span class="field">
                          <input id="cphone" title="Вашият Телефон" class="required" type="text" value="Вашият Телефон" onfocus="this.value=='Вашият Телефон'?this.value='':''" onblur="this.value==''?this.value='Вашият Телефон':''" />
                      </span>
                      <span class="field">
                          <input id="city" title="Град" class="required" type="text" value="Град" onfocus="this.value=='Град'?this.value='':''" onblur="this.value==''?this.value='Град':''" />
                      </span>
                      <span class="field">
                          <input id="postcode" title="Пощенски код" class="required" type="text" value="Пощенски код" onfocus="this.value=='Пощенски код'?this.value='':''" onblur="this.value==''?this.value='Пощенски код':''" />
                      </span>
                      <span class="field">
                          <input id="caddr" title="Адрес за доставка" class="required" type="text" value="Адрес за доставка" onfocus="this.value=='Адрес за доставка'?this.value='':''" onblur="this.value==''?this.value='Адрес за доставка':''" />
                      </span>

                      <span class="field">
                          <input id="qty" title="Количество" class="required-number" type="text" value="Количество" onfocus="this.value=='Количество'?this.value='':''" onblur="this.value==''?this.value='Количество':''" />
                      </span>



                      <input type="submit" class="type-submit" value="" />
                      <div id="contacts-overlay">&nbsp;</div>

                   </form>


                   <h2 class="img-title">Условия за доставка</h2>
                   <p>
                    Доставката се извършва в срок от 1 до 3 работни дни след заявката, <br />
                    в зависимост от населеното място. Доставката се извършва през <br />
                    работно време, така че подайте адрес, където може да ви намерят <br />
                    през деня. Заплаща се на куриера при предаване на пратката.
                   </p>


                </div>
            </div>

<? include "footer.php" ?>