msie6 = !window.XMLHttpRequest;
$(document).ready(function(){

    $("#nav a").hover(function(){
      $(this).addClass('hover');
    }, function(){
      $(this).removeClass('hover');
    });

    var location = window.location.href;
    if(location=='http://coffee.massmediapublishing.com/' || location=='http://coffee.massmediapublishing.com'){
      $("#nav a:first").addClass('active')
    }
    else{
      $("#nav a[href='" + location + "']").addClass('active');
    }


    $("a[href='#']").attr('href', 'javascript:void(0)')


    $("#subscribe-bar .type-submit").hover(function(){
        $(this).css("backgroundPosition", "0 -32px");
    }, function(){
        $(this).css("backgroundPosition", "0 0px");
    });

    $("#subscribe-bar .field input").focus(function(){
        this.value=='Запиши се тук  с твоя e-mail'?this.value='':'';
        $(this).addClass('focus');
    });
    $("#subscribe-bar .field input").blur(function(){
        this.value==''?this.value='Запиши се тук  с твоя e-mail':'';
        $(this).removeClass('focus');
    });

    var uiRight = document.createElement('mw');
    uiRight.className = 'uiRight';

    var uiLeft = document.createElement('mw');
    uiLeft.className = 'uiLeft';

    var uiBot = document.createElement('mw');
    uiBot.className = 'uiBot';

    $(".quote").append(uiRight);
    $(".quote").append(uiBot);
    $(".quote").prepend(uiLeft);

    $(".testimonials-list li:odd").addClass('even');

    $("a.color").hover(function(){
      $(this).stop();
      $(this).animate({"color":"#ffffff"}, 'fast');
    }, function(){
      $(this).stop();
      $(this).animate({"color":"#FFA9A4"}, 'fast');
    });

    $("#footerbtn a, #order-big").hover(function(){
        $("#footerbtnitem").css({"top":"auto", "bottom":"0"})
    }, function(){
        $("#footerbtnitem").css({"top":"0"})
    });

    $(".img-images a").zoombox();

    $("#contacts-form").validate(function(){
        $("#contacts-overlay").show();
        var name = $("#cname").val();
        var email = $("#cmail").val();
        var phone = $("#cphone").val();
        var message = $("#cmsg").val();
         $.post(templateurl+"mailsender.php", { name: name, email: email, phone:phone, message:message }, function(){
            $("#contacts-overlay").animate({'opacity':1}, function(){
                $("#contacts-overlay").html('<h2 style="height:35px;padding-top:10px;font-size:21px;">Съобщението е изпратено успешно.</h2>');
            });

         });
    });
    $("#order-form").validate(function(){
        $("#contacts-overlay").show();
        var name = $("#cname").val();
        var email = $("#cmail").val();
        var phone = $("#cphone").val();
        var city = $("#city").val();
        var postcode = $("#postcode").val();
        var address = $("#caddr").val();
        var qty = $("#qty").val();


         $.post("order_send.php", { name: name, email: email, phone:phone, city:city, postcode:postcode, address:address, qty:qty}, function(){
            $("#contacts-overlay").animate({'opacity':1}, function(){
                $("#contacts-overlay").html('<h2 style="height:35px;padding-top:10px;font-size:21px;">Поръчката ви е приета и<br /> ще бъде доставена до вас за 3 работни дни.</h2>');
            });

         });
    });

    $("#subscribe-bar form").validate();

    $("#contacts-form .type-submit").hover(function(){
        $(this).addClass('type-submit-hover');
    }, function(){
        $(this).removeClass('type-submit-hover');
    });
    $("#order-form .type-submit").hover(function(){
        $(this).addClass('type-submit-hover');
    }, function(){
        $(this).removeClass('type-submit-hover');
    });

    $(".toggle-fragment").click(function(){
        dis = $(this);
        if($(this).hasClass("toggle-fragment-active")){
            $(this).parent().find(".fragment-content").not(':animated').slideUp('fast', function(){
              dis.removeClass('toggle-fragment-active');
            });
        }
        else{
           $(".toggle-fragment-active").parent().find(".fragment-content").not(':animated').slideUp('fast');
           $(this).parent().find(".fragment-content").not(':animated').slideDown('fast', function(){
               $('.toggle-fragment-active').removeClass("toggle-fragment-active");
               $('html,body').animate({scrollTop: dis.offset().top});
              $(dis).addClass('toggle-fragment-active');
           });
        }
       return false;
    });


});

