<?

$cart_items = get_cart_items();
$order_id = "ORD". date("ymdHis") . rand ();
 ?>

<div id="main">
  <div class="box">
    <h2 class="boxtitle">
    
      <editable  global="true" field="module_cart_checkout_title<? print $params['module_id'] ?>">
   Please complete your order
  
   </editable>
    
    
   </h2>
    <div class="box-content">
      <? if(empty($cart_items)): ?>
      <span class="cartico">
      
      
       <editable  global="true" field="module_cart_checkout_text_empty<? print $params['module_id'] ?>">
   
  Your cart is empty
   </editable>
   
   
     
      
      
      </span>
      <? else: ?>
      <div class="richtext">
      
       <editable  global="true" field="module_cart_checkout_text_more<? print $params['module_id'] ?>">
   Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
   </editable>
   
   
   
      
      
   
      </div>
      <div class="c" style="padding-bottom: 12px;"></div>
      <div id="cart_checkout_placeholder"></div>
      
      
      <editable  global="true" field="module_cart_checkout_text2<? print $params['module_id'] ?>">
<h4>Your information:</h4>
      <br />
      <p>All the fields in this form are mandatory</p>
   </editable>
      
      
      
      
      
      
      <div class="c" style="padding-bottom: 12px">&nbsp;</div>
      <script type="text/javascript">

    datacolector = function(){

      var productsData = '';
      $("#checkout-table tbody tr").each(function(i){
        var i = i+1;
        var rowContents = $(this).find(".ct-1 a").attr("title") + '; Количество - ' + $(this).find("select").val() + '; Единична цена -' + $(this).find(".ct-3").text();
        var rowContents = rowContents.replace(/  /g, "").replace(/\n/, "");
        productsData = productsData + 'product' + i + ':"' + rowContents + '",';
      });

      var sum = 'sum:"' + $("#total").text() + '",';

      var clientData = ''

      $("#checkout-form .clabel").each(function(i){
        var i = i+1;
        if(i<8){
          var label = $(this).text();
          var label = label.replace(":", "");
          var label = label.replace("*", "");
          var value = $(this).next(".field").find("input").val();
          clientData = clientData + 'info' + i + ':"' + label + ' - ' + value + '",'
        }
      });

      var data = productsData + sum + clientData;
      var length = data.length;

      var data = data.slice(0,length-1);
      var data = "{" + data + "}";

      var data = eval('(' + data + ')');
      return data;
    }

    $(document).ready(function(){

        $("#checkout-form").validate(function(){
            var data = datacolector();
            $.post(template_url + "sendit.php", data, function(){
              Modal.overlay();
              Modal.box("<h3 style='text-align:center;padding:20px;'>Вашата заявка е изпратенa успешно</h3>", 400, 100);
            });

        });

    });

    </script>
      <form method="post" action="#" id="checkout-form">
        <div class="block">
          <label class="clabel">Клиент (имена): *</label>
          <span class="field">
          <input type="text" class="required" style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">E-mail: *</label>
          <span class="field">
          <input type="text" class="required-email" style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">Адрес за доставка: *</label>
          <span class="field">
          <input type="text" class="required" style="width: 390px" />
          </span> </div>
        <div class="block">
          <label class="clabel">Град: *</label>
          <span class="field">
          <input type="text" class="required" style="width: 166px" />
          </span>
          <label class="clabel" style="width: 105px;margin-right: 10px;">Пощенски код: *</label>
          <span class="field">
          <input type="text" class="required" style="width: 95px" />
          </span> </div>
        <div class="block">
          <label class="clabel">Телефон за връзка: *</label>
          <span class="field">
          <input type="text" class="required" style="width: 114px" />
          </span>
          <label style="width: 140px; margin-right: 10px;" class="clabel">Резервен телефон: *</label>
          <span class="field">
          <input type="text" class="required" style="width: 113px" />
          </span> </div>
        <div class="formhr">&nbsp;</div>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <div class="block" style="padding-bottom: 0">
          <label class="clabel">&nbsp;</label>
          <input type="checkbox" class="conf required" />
          <span class="uslovia">Съгласен съм с <a href="#">условията</a></span></div>
      </form>
      <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
      <a href="#" class="mainbtn right" onclick="$('#checkout-form').submit()">изпрати поръчка</a> <span class="right" style="margin:5px 20px 0 0;"><strong>*</strong> - задължителни полета </span>
      <? endif ?>
    </div>
    <!-- { box-content end  -->
  </div>
  <!--  box end } -->
</div>
<!-- /#main -->
<div id="sidebar">
  <div class="box boxmargin">
    <h2 class="boxtitle">За поръчката</h2>
    <div class="box-content"> Текст за начин на доставка и за плащане
      <!--     <a href="#" class="mainbtn" onclick="history.back();">върни се обратно</a>-->
    </div>
  </div>
</div>
<!-- /#sidebar -->
