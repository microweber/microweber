<p><strong>Запитване за наемане на автомобил   </strong></p>

<form method="post" action="#" class="rForm" id="med_form">


<div class="item">
  <div class="xitem">
      <label>Име, фамилия*</label>
      <span class="field">
          <input type="text" name="Telefon" class="required" style="width:300px" />
      </span>
  </div>
</div>

<div class="item">
  <div class="xitem">
      <label>E-mail адрес* </label>
      <span class="field">
          <input type="text" class="required-email" style="width:300px" />
      </span>
  </div>
</div>

<div class="item">
  <div class="xitem">
      <label>Телефон*</label>
      <span class="field">
          <input type="text" class="required" style="width:300px" />
      </span>
  </div>
</div>
<div class="item">
  <div class="xitem">
      <label>Автомобил</label>
      <span class="field">
          <input type="text" class="required" style="width:300px" />
      </span>
  </div>
</div>


<div class="item left" style="width: 160px;">
  <div class="xitem">
  <label>Начална дата на наемане </label>
      <span class="field">
          <input type="text" class="required XDatePicker" style="width:140px" />
      </span>
  </div>
</div>

<div class="item left" style="width: 160px;">
  <div class="xitem">
      <label>Дата на връщане</label>
      <span class="field">
          <input type="text" class="required XDatePicker" style="width:140px" />
      </span>
  </div>
</div>

<div class="c">&nbsp;</div>

<div class="item">
  <div class="xitem">
      <label>Коментар</label>
      <span class="field">
          <textarea style="width:300px" rows="" cols=""></textarea>
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

