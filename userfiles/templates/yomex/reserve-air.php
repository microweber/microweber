<style type="text/css">
.radio input{
  margin-right: 5px;
}


</style>

<form method="post" action="#" id="air-bileti" class="rForm">
 
 <?php include('reserve_dates_chose.php'); ?>

    <div style="padding-bottom: 12px;">
      <label class="label" style="padding-bottom: 7px;"><strong>Вид на пътуването</strong></label>
      <div class="c" style="padding-bottom: 10px">&nbsp;</div>
      <span class="radio">
          <input type="radio" name="vid" checked="checked" />Двупосочно пътуване
      </span>
      &nbsp;&nbsp;&nbsp;
      <span class="radio">
        <input type="radio" name="vid" />Еднопосочно пътуване
      </span>
    </div>


    <div class="item left" style="width: 220px;">
      <label class="label">От</label>
      <span class="field">
        <input class="required" type="text" />
      </span>
      <small class="block">Въведи Град/Летище</small>
    </div>

    <div class="item">
      <label class="label">До</label>
      <span class="field">
        <input class="required" type="text" />
      </span>
      <small class="block">Въведи Град/Летище</small>
    </div>


    <div class="item left" style="width: 135px">
      <label class="label">Дата на заминаване</label>
      <span class="field">
        <input style="width: 115px" class="XDatePicker" type="text" />
      </span>
    </div>
    <div class="item left" style="width: 130px;">
      <label class="label">Час на заминаване</label>
      <span class="field">
        <select style="width: 130px">
            <option value="По всяко време">По всяко време</option>
            <option value="00:00">00:00</option>
            <option value="01:00">01:00</option>
            <option value="02:00">02:00</option>
            <option value="03:00">03:00</option>
            <option value="04:00">04:00</option>
            <option value="05:00">05:00</option>
            <option value="06:00">06:00</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
            <option value="20:00">20:00</option>
            <option value="21:00">21:00</option>
            <option value="22:00">22:00</option>
            <option value="23:00">23:00</option>
        </select>
      </span>
    </div>

    <div class="c">&nbsp;</div>
    <div class="item left" style="width: 135px">
      <label class="label">Дата на връщане</label>
      <span class="field">
        <input style="width: 115px;" class="XDatePicker" type="text" />
      </span>
    </div>
    <div class="item left" style="width: 130px;">
      <label class="label">Час на връщане</label>
      <span class="field">
        <select style="width: 130px">
            <option value="По всяко време">По всяко време</option>
            <option value="00:00">00:00</option>
            <option value="01:00">01:00</option>
            <option value="02:00">02:00</option>
            <option value="03:00">03:00</option>
            <option value="04:00">04:00</option>
            <option value="05:00">05:00</option>
            <option value="06:00">06:00</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
            <option value="20:00">20:00</option>
            <option value="21:00">21:00</option>
            <option value="22:00">22:00</option>
            <option value="23:00">23:00</option>
        </select>
      </span>
    </div>

    <p><strong>Пътници</strong></p>
    <div class="item left small">
      <label class="label">Възрастен (18+)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="item left small">
      <label class="label">Дете (2-12)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>

    <div class="item left small">
      <label class="label">Бебе (0-2)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="item left small">
      <label class="label">Младеж (12-24)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="c">&nbsp;</div>
    <div class="item left small">
      <label class="label">Ученик (12-27)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="item left small">
      <label class="label">Пенсионер (65+)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="item left small">
      <label class="label">Моряк (18+)</label>
      <span class="field">
        <select>
            <option value="0"></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>
      </span>
    </div>
    <div class="c">&nbsp;</div>
    <div class="item">
      <label class="label">Предпочитани авиокомпании</label>
      <span class="field">
        <textarea></textarea>
      </span>
    </div>

    <div class="item">
      <label class="label">Максимална цена</label>
      <span class="field">
        <input type="text" style="width:100px;" />
      </span>&nbsp;EUR
    </div>

    <div class="item">
      <label class="label">Класа на пътуване</label>
      <span class="field">
        <select>
            <option value="икономична">икономична</option>
            <option value="бизнес">бизнес</option>
            <option value="първа">първа</option>
        </select>
      </span>
    </div>

    <div>
      <label class="label"><input type="checkbox" id="direktni" class="left" style="margin-right: 5px;" />Само директни полети</label>
    </div>

<div class="c" style="padding-bottom: 10px;">&nbsp;</div>
     <label class="label"><strong>Възможност за промяна датата на заминаване:</strong></label>
     <div class="c" style="padding: 3px">&nbsp;</div>
    <div class="item left" style="width:135px;">


      <label class="label">Дата на заминаване</label>
      <span class="field">
        <input style="width: 115px" class="XDatePicker" type="text" />
      </span>
    </div>

    <div class="item left" style="width: 130px;">
      <label class="label">Час на заминаване</label>
      <span class="field">
        <select style="width: 130px">
            <option value="По всяко време">По всяко време</option>
            <option value="00:00">00:00</option>
            <option value="01:00">01:00</option>
            <option value="02:00">02:00</option>
            <option value="03:00">03:00</option>
            <option value="04:00">04:00</option>
            <option value="05:00">05:00</option>
            <option value="06:00">06:00</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
            <option value="20:00">20:00</option>
            <option value="21:00">21:00</option>
            <option value="22:00">22:00</option>
            <option value="23:00">23:00</option>
        </select>
      </span>
    </div>

    <div class="c">&nbsp;</div>
    <br />
    <label class="label"><strong>Възможност за промяна на дата пристигане</strong></label>
    <div class="c" style="padding: 3px">&nbsp;</div>
    <div class="item left">
    <label class="label">Дата на пристигане</label>
      <span class="field">
        <input style="width: 115px" class="XDatePicker" type="text" />
      </span>
    </div>

    <div class="item left" style="width: 130px;">
      <label class="label">Час на пристигане</label>
      <span class="field">
        <select style="width: 130px">
            <option value="По всяко време">По всяко време</option>
            <option value="00:00">00:00</option>
            <option value="01:00">01:00</option>
            <option value="02:00">02:00</option>
            <option value="03:00">03:00</option>
            <option value="04:00">04:00</option>
            <option value="05:00">05:00</option>
            <option value="06:00">06:00</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
            <option value="20:00">20:00</option>
            <option value="21:00">21:00</option>
            <option value="22:00">22:00</option>
            <option value="23:00">23:00</option>
        </select>
      </span>
    </div>
     <div class="c">&nbsp;</div>
    <div id="user-info">
    

    
<div class="item">
    <div class="xitem" style="width:140px">
        <label>Обръщение</label>
        <span class="field">
            <select style="width: 120px;">
                <option>Г-н</option>
                <option>Г-жа</option>
        		<option>Г-ца</option>
            </select>
        </span>
    </div>
    <div class="xitem" style="width:140px">
        <label>Име *</label>
        <span class="field">
            <input type="text" name="Ime" class="required" style="width:120px" />
        </span>
    </div>
    <div class="xitem" style="width:140px">
        <label>Фамилия *</label>
        <span class="field">
            <input type="text" name="Familia" class="required" style="width:120px" />
        </span>
    </div>

  </div>

  <div class="item left" style="width:190px">
    <div class="xitem">
        <label>E-mail *</label>
        <span class="field">
            <input type="text" name="Email" class="required-email-equal" style="width:170px" />
        </span>
    </div>
  </div>



  <div class="item left" style="width:190px">
      <div class="xitem">
            <label>Потвърждение на E-mail *</label>
            <span class="field">
                <input type="text" name="PotvarjdenieEmail" class="required-email-equal" style="width:170px" />
            </span>
      </div>
  </div>

  <div class="item">
    <div class="xitem" style="width:140px">
        <label>Телефон *</label>
        <span class="field">
            <input type="text" name="Telefon" class="required" style="width:120px" />
        </span>
    </div>


  </div>

  <script>
  $(document).ready(function(){
        $("input[name='mailform_subject']").val('Самолетни Резервации');
  });
  </script>
  <?php include('reserve_include.php'); ?>

  <div class="item">
   <div class="xitem" style="width:340px">
        <label>
            <span>Допълнителна информация</span>
            <small style="display: block;">(тук можете да посочите, ако имате други специални изисквания)</small>
        </label>

        <span class="field">
            <textarea style="width:340px"></textarea>
        </span>
    </div>
  </div>

</div>


<div class="wrap" style="padding-bottom: 12px;">
    <div class="xitem" style="width:140px">
            <label style="padding-bottom: 5px;" class="block"><strong>Начин на плащане*</strong></label>

              <ul style="width: 200px;">
                <li><input type="radio" id="vbroi" name="NachinNaPlashtane" checked="checked" /> В брой в офиса на фирмата</li>
                <li><input type="radio" id="vbanka" name="NachinNaPlashtane" /> По банков път</li>
                <li><input type="radio" id="vkarta" name="NachinNaPlashtane" /> С кредитна карта *</li>
              </ul>
              <div style="display: none" id="nachin-txt-vbroi">
                  София<br />
                  ул. "Иван Вазов" 12А<br />
                  (02) 981 09 56<br />
                  (02) 981 09 63<br />
                  <a href="mailto:yomex@yomexbg.com">yomex@yomexbg.com</a>
              </div>
              <div style="display: none" id="nachin-txt-vbanka">
               <p><strong>Банкова информация</strong> </p>

                <p>Банка – Уникредит Булбанк <br />
                <strong>BIC:UNCRBGSF </strong></p>

                <p>Сметка в лв.  <br />
                <strong>BG34 UNCR 7000 1503 9330 48</strong></p>

              </div>
              <div style="display: none" id="nachin-txt-vkarta">
                <span class="kreditnikarti">&nbsp;</span>
                <p>Върху плащанията с кредитна карта се начислява банкова такса от 3%</p>
              </div>
              <div id="nachin-txt">
                София<br />
                ул. "Иван Вазов" 12А<br />
                (02) 981 09 56<br />
                (02) 981 09 63<br />
                <a href="mailto:yomex@yomexbg.com">yomex@yomexbg.com</a>

             </div>
             <?php /*
             <p style="width: 500px;color: #8E1756"> На следващата стъпка ще получите информация за разплащането онлайн. </p>
             */ ?>




    </div>


  </div>

<!--<ul style="width: 300px;height: 25px;">
                <li><span><input type="checkbox" class="required" name="Usloviia">  Приемам <a href="javascript:;" onclick="pop('usloviia-za-rezervaciia')">условията за резервация</a></span></li>

              </ul>-->

    <input type="submit" value="Изпрати" class="search" />



<ul style="padding: 10px 0 0 17px;list-style: disc">
  <li><small>Чрез този формуляр Вие извършвате онлайн заявка за резервация на самолетен билет.</small></li>
<?php /*
  <li><small>До 24 часа (в работни дни) ще получите потвърждение по имейл за наличните стаи и детайлите по резервацията.</small></li>
  <li><small>За да гарантирате своята резервация е необходимо да направите пълно разплащане по избрания от Вас начин.</small></li>
*/ ?>
</ul>


</form>

<script type="text/javascript">

var pop = function(url){
  var url = site_url + url;
  var window_center = $(window).width()/2-300 + 'px';
  var printPopup=window.open(url,"myWin","menubar,scrollbars,left="+ window_center+",top=90px,height=400px,width=600px");
}

var rand = function(){
   return Math.floor(Math.random()*9999);
}

var AirDataCollector = function(){

var vid = 'item' + rand() + ':"Вид на пътуването - ' + $("input[name='vid']:checked").parent().text() + '",';
  var location = window.location.href;
  var location = 'item' + rand() + ':"Адрес -  '+ location + '",' ;

var subject = 'subject:"Заявка за самолетни билети",';

var main = '';
$(".item label").each(function(){
  var label = $(this).text();
  var val = $(this).next(".field").find("select, textarea, input").eq(0).val();
  main = main + 'item' + rand() + ':"' + label + ' - ' + val + '",';
});

var direkt = "";
if($(document.getElementById('direktni')).is(":checked")){
  direkt = "Да";
}
else{
  direkt = "Не";
}

var direktni = 'item' + rand() + ':"Само директни полети - ' + direkt + '"';

var data = subject + location + vid + main + direktni;

var data = data.replace(/  /g, "").replace(/\n/g, "");

var data = eval('({' + data + '})');

return data;

}



  $(document).ready(function(){
    $(".gtitle:first").clone(true).prependTo(".rForm");
    $(".XDatePicker").datepicker();
    $("input[name='NachinNaPlashtane']").click(function(){
      var id = $(this).attr("id");
      $("#nachin-txt").html($("#nachin-txt-" + id).html());
    });

      $("#air-bileti").validate(function(){

       var data = AirDataCollector();

        $.post(template_url + "mail_sender.php", data, function(data){
           //alert(data)
          Modal.box("<h2 style='text-align:center;padding:20px'>Вашата резервация е изпратена успешно.</h2>", 400, 150);
          Modal.overlay();
        });

      });


  });
</script>


