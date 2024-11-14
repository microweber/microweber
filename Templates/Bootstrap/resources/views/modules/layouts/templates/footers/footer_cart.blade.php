<script>
    AddToCartModalContent = window.AddToCartModalContent || function (title) {
        var html = ''
            + '<section style="text-align: center;">'
            + '<h5>' + title + '</h5>'
            + '<p><?php _ejs("has been added to your cart"); ?></p><br />'
            + '<div><a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="pull-left mt-2"><?php _e("Continue shopping"); ?></a>'
            + '<a href="<?php print checkout_url(); ?>" class="btn-d pull-right"><?php _ejs("Checkout"); ?></a></section><div class="clearfix"></div></div>';
        return html;

    }

    function cartModalBindButtons(step) {

        $('.js-show-step').off('click');
        $('.js-show-step').on('click', function () {
            var has_error = 0;
            var step = $(this).data('step');
            if (step == 'payment-method') {

                $('.js-delivery-address input').each(function () {
                    if (!this.checkValidity()) {
                        mw.notification.warning('<?php _e("Please fill the required fields"); ?>');
                        // ..alert($(this).prop('name') + ' is not valid');
                        $(this).addClass('error');
                        has_error = 1;
                    } else {
                        $(this).removeClass('error');
                    }

                });

                if (has_error) {
                    step = 'delivery-address'
                }
            }

            $('.js-show-step').removeClass('active');

            $('[data-step]').removeClass('active');
            $('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');


            step1 = '.js-' + step;
            $('.js-step-content').hide();
            $(step1).show();
            $(this).addClass('active');
        });
    }

    $(document).ready(function () {
        mw.on('mw.cart.add', function (event, data) {
            $("#js-ajax-cart-checkout-process").reload_module()
            $(".bs-cart a").trigger('click');
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    });
</script>


<!-- Login Modal -->
<div class="modal  login-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="js-login-window">
                    <div class="icon"><i class="material-icons">person</i></div>

                    <div type="users/login" id="loginModalModuleLogin"></div>
                </div>

                <div class="js-register-window" style="display:none;">
                    <div class="icon"><i class="material-icons">exit_to_app</i></div>

                    <div type="users/register" id="loginModalModuleRegister"></div>

                    <p class="or"><span>or</span></p>

                    <div class="act login">
                        <a href="#" class="js-show-login-window"><span>Back to Login</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (user_id()): ?>
    <!-- Orders Modal -->
    <div class="modal fade my-orders-modal" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="ordersModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <module type="users/orders"/>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
