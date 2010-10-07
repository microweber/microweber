<script type="text/javascript">
$(document).ready(function(){
$("#ziptravel_pdf").modal("iframe-auto");
});
</script>

Йомекс ви предлага едно добре организирано, спокойно и сигурно пътуване чрез допълнителната си услуга "медицинска застраховка". Ние работим с един от лидерите в сферата на международните застраховки - QBE International Insurance Ltd.
QBE Insurance Group
"Кю Би И Иншурънс Груп" е един от водещите в света международни застрахователи и презастрахователи, с централа в Сидни, Австралия. Компанията оперира в 36 страни, с над 7000 служители и присъства на всички ключови застрахователни пазари, а от 1999 е и на българския пазар.
Медицинската застравховка при пътуване в чужбина, която QBE предлага, е следната:
Медицински разноски с асистанс*, валидна за чужбина
КЮ БИ И София предлага на клиентите си Застраховка "Помощ при пътуване с асистанс", валидна за цял свят. Застраховката покрива:

<ul>
  <li>Разноски за медицинско обслужване в случай на злополука или акутно*заболяване;</li>
  <li>Медицинско транспортиране и репатриране;</li>
  <li>Репатриране на тленни останки.</li>
  <li>Спешна стоматологична помощ</li>
</ul>

Компанията осигурява и 24 часова телефонна връзка при спешни случаи с почти всяка точка на света. Пострадалият ще получи помощ при здравословен проблем и в случай на необходимост от болнична услуга, Кю Би И София се разплаща директно с болничното заведение.
За групови и семейни пътувания се прилагат специални отстъпки
Лимитите на отговорност по застраховката са съобразени с европейските норми и стандарти


<p>Общи условия на застраховка "Помощ при пътуване (Асистанс) - Травълър" може да видите <a href="http://www.ziptravel.bg/uploads/documents/BG.pdf" id="ziptravel_pdf">ТУК</a>     </p>
<p><strong>Можем да Ви изпратим оферта със стойността на всички видове покрития, които предлага Кю Би И, съобразно Вашето пътуване. За целта моля, подайте нужната информация към нас.:</strong></p>



<form method="post" action="#" class="rForm" id="med_form">


<div class="item">
  <div class="xitem">
      <label>Име и фамилия * (по паспорт на латиница) </label>
      <span class="field">
          <input type="text" name="Telefon" class="required" style="width:300px" />
      </span>
  </div>
</div>

<div class="item">
  <div class="xitem">
      <label>Дата на раждане *</label>
      <span class="field">
          <input type="text" class="required" style="width:300px" />
      </span>
  </div>
</div>

<div class="item">
  <div class="xitem">
      <label>Държавата, която ще посетите е *</label>
      <span class="field">
          <input type="text" class="required" style="width:300px" />
      </span>
  </div>
</div>

<div style="padding-bottom: 5px;"><label><strong>Начална и крайна дата на пътуването *</strong></label> </div>
<div class="item left" style="width: 160px;">
  <div class="xitem">
  <label>От</label>
      <span class="field">
          <input type="text" class="required XDatePicker" style="width:140px" />
      </span>
  </div>
</div>

<div class="item left" style="width: 160px;">
  <div class="xitem">
      <label>До</label>
      <span class="field">
          <input type="text" class="required XDatePicker" style="width:140px" />
      </span>
  </div>
</div>

<div class="c">&nbsp;</div>

<div class="item">
  <div class="xitem">
      <label>Телефон за връзка: *</label>
      <span class="field">
          <input type="text" class="required" style="width:300px" />
      </span>
  </div>
</div>

<div class="item">
  <div class="xitem">
      <label>Е-mail за контакт: * </label>
      <span class="field">
          <input type="text" class="required-email" style="width:300px" />
      </span>
  </div>
</div>




 <input type="submit" value="Изпрати" class="search" />



</form>



<script type="text/javascript">

var rand = function(){
   return Math.floor(Math.random()*9999);
}
var AirDataCollector = function(){
  var location = window.location.href;
  var location = 'item' + rand() + ':"Адрес -  '+ location + '",' ;
  var main = '';
  $(".item label").each(function(){
    var label = $(this).text();
    var val = $(this).next(".field").find("select, textarea, input").eq(0).val();
    main = main + 'item' + rand() + ':"' + label + ' - ' + val + '",';
  });

  var data = location + main;

  var data = data.replace(/  /g, "").replace(/\n/g, "");

  var data = eval('({' + data + '})');

  return data;

}



  $(document).ready(function(){
    $(".XDatePicker").datepicker();


      $("#med_form").validate(function(){

       var data = AirDataCollector();

        $.post("http://pluton.superhosting.bg/~yomexbgc/userfiles/templates/yomex/mail_sender_for_reserve_hotels.php", data, function(data){
           alert(data)
          //Modal.box("<h2 style='text-align:center'>Вашата резервация е изпратена успешно.</h2>");
          //Modal.overlay();
        });

      });


  });
</script>

