<? $view_order = url_param('view_order'); 

$nav = url_param('nav'); 
?>
<script>
  $(document).ready(function() {
    //$("#orders_tabs").tabs();
  });
  </script>
 <script type="text/javascript">
function ops_list($kw){
   
   
   
   data1 = {}
   data1.module = 'admin/orders/list';
   if(($kw == false) || ($kw == '') || ($kw == undefined)){
	$kw = null;  
	
   } else {
	data1.keyword = $kw;
 
	
   }
    
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
 
   $('#orders_placeholder').html(resp);
   
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
   
 
}







$(document).ready(function() {
				
	 
						   

  $("#content_search").onStopWriting(function(){
       ops_list(this.value);
  });
   $("#content_search_btn").click(function(){
       ops_list($("#content_search").val());
  });
  
    


});
</script>
<div class="box radius">
  <div class="box_content_small_margin">
    <div class="shop_nav_main">
      <h2 class="box_title">Online Shop</h2>
      <ul class="shop_nav">
        <? if($view_order  == false):?>
        <li><a  href="<? print site_url('admin/action:shop');?>" <? if($nav == false):?>  class="active"  <? endif;  ?>><span>Orders list</span></a></li>
        <li><a  <? if($nav == 'options'):?>  class="active"  <? endif;  ?>  href="<? print site_url('admin/action:shop/nav:options');?>"><span>Options</span></a></li>
         <li><a  <? if($nav == 'promo'):?>  class="active"  <? endif;  ?>  href="<? print site_url('admin/action:shop/nav:promo');?>"><span>Promo codes</span></a></li>
        <? else : ?>
        
        <li><a href="#" class="active"><span>Viewing order: <? print $view_order ?> </span></a></li>
        <li><a href="<? print site_url('admin/action:shop');?>"><span>Back to orders list</span></a></li>
         
        <? endif;  ?>
      </ul> 
       <? if($nav == false):?> 
       <? if($view_order  == false):?>
      <div class="right" style="padding:5px; padding-top:10px; padding-right:25px;">
             <input type="text" default="Search orders"  class="content_search_wide" id="content_search"  />
            <a href="#" id="content_search_btn" class="btn3 hovered">Search</a> </div>
             <? endif;  ?>
              <? endif;  ?>
    </div>
    <div class="c">&nbsp;</div>
    <? if($view_order  == false and $nav == false):?>
    <div id="orders_placeholder"><mw module="admin/orders/list" /></div>
      
   
    <? endif;  ?>
    <? if($nav == 'options'):?>
   
      <mw module="admin/orders/options" />
    
    <? endif;  ?>
    
    
     <? if($nav == 'promo'):?>
   
      <mw module="admin/orders/promo" />
    
    <? endif;  ?>
    
    
    <? if($view_order != false):?>
<br />
 

   
      <mw module="admin/orders/edit_order" id="<? print $view_order ?>" />
 
    <? endif;  ?>
  </div>
</div>
