<script type="text/javascript">
var template_url = '<?php print TEMPLATE_URL; ?>';
var img_url = '<?php print TEMPLATE_URL; ?>img/';
var imgurl = '<?php print TEMPLATE_URL; ?>img/';
var site_url = '<?php print site_url(); ?>';
var ajax_mail_form_url = '<?php print site_url('main/mailform_send'); ?>';
var ajax_mail_form_url_validate = '<?php print site_url('main/mailform_send/validate:yes'); ?>';
 var ajax_mail_form_url = '<?php print site_url('main/mailform_send2'); ?>';
var current_url = '<?php print current_url(); ?>';
var imgurl = '<?php print TEMPLATE_URL; ?>img/';
var thankyoumsg = ""
+"<div style='text-align:center;padding:20px;'><h2>Thank you for your interest in Omnitom yoga &amp; fusion wear.</h2><br />"
+"<h2>You will be contacted shortly with response to your request</h2></div>";
</script>
<!--<link rel="icon" href="<?php print TEMPLATE_URL; ?>images/favicon.gif" type="image/gif" >-->
<link rel="shortcut icon" href="<?php print TEMPLATE_URL; ?>favicon.ico" />
<link rel="favicon" href="<?php print TEMPLATE_URL; ?>favicon.ico" />
<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.3.2");</script>
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>style.css" type="text/css" media="screen"  />
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/modal.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>functions.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/validate.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/jquery.form.js"></script>
<!--<link rel="stylesheet" href="http://omnitom.com/font/styles.css" type="text/css" media="screen"  />
-->

<script type="text/javascript" src="http://omnitom.com/facelift/flir.js"></script>
<!--[if IE]><?php echo '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<script src="<?php print TEMPLATE_URL; ?>js/jquery.idle/idle-timer.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>jquery.magnifier.js"></script>
<script type="text/javascript">


            $(document).ready(function(){
                $("a").each(function(){
                  var href = $(this).attr("href");
                  var sURL = "<?php print site_url(); ?>";
                  if(href.indexOf(sURL) == -1 && href.indexOf("javascript") ==-1){
                    $(this).attr("target", "_blank");
                    //$(this).html(href)
                  }
                });




            });


    (function($){



        $(document).bind("idle.idleTimer", function(){

            //$("#status").html("User is idle :(").css("backgroundColor", "silver");

			//	alert(current_url);

			parent.location.href=current_url;

        });

        $(document).bind("active.idleTimer", function(){

            // $("#status").html("User is active :D").css("backgroundColor", "yellow");

        }).trigger('active.idleTimer');;

        $.idleTimer(777000);

    })(jQuery);



</script>
<script type="text/javascript">
                                $(document).ready(function(){




                                     $("#main_magnify").magnify({
									 lensWidth: 200,
            							lensHeight: 200,
                                        link: false
                                     });

$("#dio-sensor").click(function(){
    $("#overlay").show();
    var mImg = $(".zoom").attr("href");
    var mImage = new Image();
    document.getElementById('modal').appendChild(mImage);
    mImage.onload = function(){
         var mWidth = this.offsetWidth;
         var mHeight = this.offsetHeight;
         $("#modal").css("top", ($("#main_magnify img").offset().top + 'px'));
         $("#modal").css("left", ($("#main_magnify img").offset().left + 'px'));
         $("#modal").css("width", ($("#main_magnify img").width() + 'px'));
         $("#modal").css("height", ($("#main_magnify img").height() + 'px'));
         var top_final_place = $(window).scrollTop() + $(window).height()/2 - mHeight/2;
         $("#modal").css("visibility", "visible");
         $("#modal").animate({width:mWidth, height:mHeight, left:$(window).width()/2-mWidth/2, top: top_final_place, opacity:1}, 'slow', function(){
            $("#modal").append(app_close_append);
            $("#modal").css("border", "solid 4px #3D638D");
         });
    }
    mImage.src=mImg;
    return false;
});
$("#overlay, .closeModal").click(function(){
       $("#modal").css("visibility", "hidden").empty().removeAttr("style");$("#overlay").hide();
});


$("#main_image_holder ul a:first").click();

                                });

                                function close_modal(){
                                    $("#modal").css("visibility", "hidden").empty().removeAttr("style");$("#overlay").hide();
                                } 
                            </script>
<script type="text/javascript">

function change_shop_currency($val){
 // alert(($("#shop_currency_celector").val()));
 
 $c =  $val;
 $.post("<?php print site_url('ajax_helpers/cart_set_shop_currency'); ?>", { currency:$val , the_val: $c, random_stuff: Math.random() },
  function(data){
	 //showShippingCost(); 
	 window.location.reload()
  });
 
 

}


// prepare the form when the DOM is ready 
$(document).ready(function() {





    $(".mail_form_ajax_send").validate();
    var options = {
        //target:        '#output1',   // target element(s) to be updated with server response
		url:       '<?php print site_url('main/mailform_send2'); ?>'  ,
		clearForm: true,
		type:      'post',
        beforeSubmit:  mail_form_showRequest,  // pre-submit callback
        success:       mail_form_showResponse  // post-submit callback

    };

    $('.mail_form_ajax_send').submit(function(){
        $(this).ajaxSubmit(options);
        return false;

    });
}); 
 
// pre-submit callback
function mail_form_showRequest(formData, jqForm, options) {
    var TF = true;
if($(".mail_form_ajax_send textarea.error").exists() || $(".mail_form_ajax_send input.error").exists()){
        TF = false;

    }
    return TF;
} 
 
// post-submit callback
function mail_form_showResponse(responseText, statusText)  {


    Modal.box(thankyoumsg, 450, 200);
    $("#modalbox").css("background", "white");
}
</script>
<script type="text/javascript">




function  cart_ajax_form_submit(){ 
$('.cart_ajax_form').submit();
}

function  cart_ajax_empty(){ 

		var answer = confirm("Are you sure you want to empty your bag?")
			if (answer){
				//alert("Bye bye!")
				//
				$.post("<?php print site_url('ajax_helpers/cart_itemsEmptyCart'); ?>", { name: "John", time: "2pm" },
  function(data){
    //alert("Data Loaded: " + data);

    Modal.box('<style>#modalbox{background-image:none}</style><h2 style="text-align:center;padding:30px 0;">Your bag is now empty. We will take you to the shop, so you can add some items.</h2>', 470, 200);
	
	//alert("Your bag is now empty. We will take you to the shop, so you can add some items.");
	window.location="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>";
	//
	//$("#the_cart_qty").html(data);
	
//location.reload(true)

  });

				
			}
			else{
				//alert("Thanks for sticking around!")
			}


}
// prepare the form when the DOM is ready 
$(document).ready(function() {
update_the_cart_qty_in_header();
    var options = {
        //target:        '#output1',   // target element(s) to be updated with server response
		url:       '<?php print site_url('ajax_helpers/cart_itemAdd'); ?>'  ,
		clearForm: true,
		type:      'post',
      //  beforeSubmit:  cart_ajax_form_showRequest,  // pre-submit callback
        success:       cart_ajax_form_showResponse  // post-submit callback
    };

    $('.cart_ajax_form').submit(function(){
        $(this).ajaxSubmit(options);
        return false;

    });
});    
 
 
// post-submit callback 
function cart_ajax_form_showResponse(responseText, statusText)  { 
      update_the_cart_qty_in_header();
      var msg = ''
      + '<style>#modalbox{background-image:none}</style>'
      +'<h2 style="text-align:center;padding:30px 0;">Your items are added to the shopping bag!</h2><br />'
      +'<a class="left" style="margin-left:20px;" href="javascript:Modal.xclose();">Continue Shopping</a>'
      +'<a class="left" style="margin-left:20px;" href="' + site_url + 'shopping-cart">View Bag</a>'
      +'<a class="big_btn right" style="margin-right:20px;" href="' + site_url +  'checkout"><span><i class="mw_ibag">&nbsp;</i>Checkout</span></a>';

Modal.box(msg, 470, 150);
}


function update_the_cart_qty_in_header(){ 
//$("#the_cart_qty").fadeOut();
$.post("<?php print site_url('ajax_helpers/cart_itemsGetQty'); ?>", function(data){
	$("#the_cart_qty").html(data);
	//$("#the_cart_qty").fadeIn();
  });

}


function cart_itemsGetTotal(){
$.post("<?php print site_url('ajax_helpers/cart_itemsGetTotal'); ?>", function(data){
	return data;
  });
}







</script>
