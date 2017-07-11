TempateFunctions = {
    contentHeight: function () {

        /**************************************
         Minimum height for the Main Container
         **************************************/
        if (self !== top) {
            return false;
        }

        var content = document.getElementById('content-holder'),
            footer = document.getElementById('footer'),
            header = document.getElementById('header');

        $(content).css('minHeight', $(window).height() - $(header).outerHeight(true) - $(footer).outerHeight(true));

    }
}


$(document).ready(function () {

    TempateFunctions.contentHeight();
    if (typeof(mw.msg.product_added) == "undefined") {
        mw.msg.product_added = "Your product is added to the shopping cart";
    }

    // $(window).bind('mw.cart.add', function () {
    //     var modal_html = ''
    //         + '<div id="mw-product-added-popup-holder"> '
    //         + '<h4>' + mw.msg.product_added + '</h4>'
    //         + '<div id="mw-product-added-popup" class="text-center" style="width:210px;"> '
    //         + ' </div>';
    //     +' </div>';
    //     Alert(modal_html)
    //     mw.load_module('shop/cart', '#mw-product-added-popup', false, {template: 'small'});
    //
    //
    // });

    $(window).bind('mw.cart.add', function(event, data){

        if(document.getElementById('AddToCartModal') === null){

            AddToCartModal = mw.modal({

                content:AddToCartModalContent(data.product.title),

                template:'mw_modal_basic',

                name:"AddToCartModal",

                width:400,

                height:200

            });

        }

        else{

            AddToCartModal.container.innerHTML = AddToCartModalContent(data.product.title);

        }

    });

});

$(window).bind('load resize', function (e) {

    TempateFunctions.contentHeight();

});






