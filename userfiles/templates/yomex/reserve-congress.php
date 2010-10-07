<style type="text/css">
.radio input {
	margin-right: 5px;
}
.item small {
	width: 200px;
	display: block;
	clear: both;
}
</style>
<form method="post" action="#" id="air-bileti" class="rForm">
  <!--<div class="item left abshidden">
    <div class="xitem">
        <label>URL: </label>
        <span class="field">
            <input type="text" name="url" value="<?php print base64_decode( $_GET['site_url']); ?>"  style="width:170px" />
        </span>
    </div>
  </div>-->
  <?php include('reserve_dates_chose.php'); ?>
  <div class="item left" style="width: 240px;">
    <div class="xitem" style="width:140px">
      <label>Вид събитие:</label>
      <span class="field">
      <select>
        <option value="семинар">семинар</option>
        <option value="конференция">конференция</option>
        <option value="форум">форум </option>
        <option value="друго">друго</option>
      </select>
      </span> </div>
  </div>
  <?php include('reserve_include.php'); ?>
  <div class="item">
    <div class="xitem" style="width:190px">
      <label>Брой хора</label>
      <span class="field">
      <input type="text" name="Ime" class="" style="width:160px" />
      </span>
      <div class="c">&nbsp;</div>
      <small>ориентировъчен брой участници</small> </div>
  </div>
  <div class="c" style="padding-top: 10px">&nbsp;</div>
  <label><strong>Период на провеждане:</strong></label>
  <div class="c">&nbsp;</div>
  <small>Моля, посочете брой дни за повеждане на събитието и предпочитаните от Вас дати или период.</small>
  <div class="item small left" style="margin-right: 10px;">
    <div class="xitem"> <span class="field">
      <textarea style="width: 475px"></textarea>
      </span> </div>
  </div>
  <?php /*
    <div class="item small left" style="margin-right: 10px;">
    <div class="xitem">
        <label>От</label>
        <span class="field">
            <input type="text" name="Ime" class="XDatePicker" />
        </span>
    </div>
    </div>
<div class="item small left">
    <div class="xitem">
        <label>До</label>
        <span class="field">
            <input type="text" name="Ime" class="XDatePicker"  />
        </span>
    </div>
    </div>
*/ ?>
  <div class="c">&nbsp;</div>
  <div class="item">
    <div class="xitem" style="width:140px">
      <label>Подредба на залата</label>
      <span class="field">
      <select>
        <option value="театрална">театрална</option>
        <option value="класна стая">класна стая</option>
        <option value="П-образна">П-образна</option>
        <option value="друга">друга</option>
      </select>
      </span> </div>
  </div>
  <div class="item left" style="width: 240px;">
    <div class="xitem" style="width:220px">
      <label>Кафе-паузи</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span>
      <div class="c">&nbsp;</div>
      <small>брой кафе-паузи на ден, какво да включват</small> </div>
  </div>
  <div class="item">
    <div class="xitem" style="width:220px">
      <label>Други хранения</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span> </div>
  </div>
  <div class="item left" style="width: 240px;">
    <div class="xitem" style="width:220px">
      <label>Техническо оборудване</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span> </div>
  </div>
  <div class="item">
    <div class="xitem" style="width:220px">
      <label>Настаняване</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span>
      <div class="c">&nbsp;</div>
      <small>В случай, че бъде необходимо настаняване за участниците, моля, посочете необходимия брой и вид стаи</small> </div>
  </div>
  <div class="item left" style="width:240px">
    <div class="xitem">
      <label>Предпочитани места за провеждане</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span>
      <div class="c">&nbsp;</div>
      <small>Aко има такива; ако не – моля, посочете Вашите изисквания към мястото на провеждане на събитието</small> </div>
  </div>
  <div class="item left" style="width: 240px;">
    <div class="xitem">
      <label>Други услуги</label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span>
      <div class="c">&nbsp;</div>
      <small>Моля, посочете, ако са необходими други услуги освен гореизброените</small> </div>
  </div>
  <div class="c">&nbsp;</div>
  <div class="item">
    <div class="xitem" style="width:220px">
      <label>Допълнителни изисквания </label>
      <span class="field">
      <textarea style="width: 200px"></textarea>
      </span>
      <div class="c">&nbsp;</div>
      <small>моля, посочете, ако имате допълнителни изисквания</small> </div>
  </div>
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
        </span> </div>
      <div class="xitem" style="width:140px">
        <label>Име *</label>
        <span class="field">
        <input type="text" name="Ime" class="required" style="width:120px" />
        </span> </div>
      <div class="xitem" style="width:140px">
        <label>Фамилия *</label>
        <span class="field">
        <input type="text" name="Familia" class="required" style="width:120px" />
        </span> </div>
    </div>
    <div class="item left" style="width:190px">
      <div class="xitem">
        <label>E-mail *</label>
        <span class="field">
        <input type="text" name="Email" class="required-email-equal" style="width:170px" />
        </span> </div>
    </div>
    <div class="item left" style="width:190px">
      <div class="xitem">
        <label>Потвърждение на E-mail *</label>
        <span class="field">
        <input type="text" name="PotvarjdenieEmail" class="required-email-equal" style="width:170px" />
        </span> </div>
    </div>
    <div class="item">
      <div class="xitem" style="width:140px">
        <label>Телефон *</label>
        <span class="field">
        <input type="text" name="Telefon" class="required" style="width:120px" />
        </span> </div>
    </div>
    <?php /*
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
  */ ?>
  </div>
  <!--<div class="wrap" style="padding-bottom: 12px;">
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
<ul style="width: 300px;height: 25px;">
                <li><span><input type="checkbox" class="required" name="Usloviia">  Приемам <a href="javascript:;" onclick="pop('usloviia-za-rezervaciia')">условията за резервация</a></span></li>

              </ul>-->
  <input type="submit" value="Изпрати" class="search" />
  <ul style="padding: 10px 0 0 17px;list-style: disc">
    <li><small>Чрез този формуляр Вие извършвате онлайн запитване за организиране на събитие.</small></li>
    <li><small>До 24 часа (в работни дни) ще се свържем с вас на посочените контакти, за да доуточним подробностите по Вашата заявка.</small></li>
    <?php /*
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
  var location = window.location.href;
  var location = 'item' + rand() + ':"Адрес -  '+ location + '",' ;
  var main = '';
  $(".item label").each(function(){
    var label = $(this).text();
	//var name = $(this).text();
    var val = $(this).next(".field").find("select, textarea, input").eq(0).val();
	 
    main = main + 'item' + rand() + ':"' + label + ' - ' + val + '",';
  });

  var subject = 'subject:"Заявка за конгресен туризъм",';

  var data = subject + location + main;

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

        $.post(template_url+"mail_sender.php", data, function(data){

         Modal.box("<h2 style='text-align:center;padding:20px'>Вашата резервация е изпратена успешно.</h2>", 400, 150);
          Modal.overlay();
        });

      });


  });
</script>
