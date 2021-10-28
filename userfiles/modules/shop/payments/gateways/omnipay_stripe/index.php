<script src="https://js.stripe.com/v3/"></script>

<div id="stripeRootStart"></div>
<script>

    var stripe_result_token_global = null;

    async function initStripeSetCardToken() {


        var result = await stripe.createToken(card);

        if (result.error) {
            // Inform the customer that there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            $('#stripeToken').remove();

            return false;
        } else {
            // Send the token to your server.
            $('#stripeToken').remove();

            stripe_result_token_global = result.token.id;
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('id', 'stripeToken');
            hiddenInput.setAttribute('value', result.token.id);
            $(stripe_form)[0].appendChild(hiddenInput);

            stripe_form.submit();
            return true;
        }

    }

    async function handleStripeForm(event) {
        if (!stripe_result_token_global) {
            event.preventDefault();
            try {
                var result = await initStripeSetCardToken();
            } catch (error) {

            }
        }


    }


    function initStripe() {


        stripe = Stripe('<?php echo get_option('stripe_publishable_api_key', 'payments');?>');
        elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '16px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        card.on('ready', function () {

                try {

                    stripe_form = $('#stripeRootStart').closest('form');

                    if (stripe_form) {
                        stripe_form[0].addEventListener('submit', handleStripeForm);
                     }

                } catch (error) {

                }


            }
        )


        mw.on('mw.cart.paymentMethodChange', function () {
            stripe_form = $('#stripeRootStart').closest('form');
            stripe_form[0].removeEventListener('submit', handleStripeForm);
        })

    }

    $(document).ready(function () {
        setTimeout(initStripe, 1000);

    });
</script>


<div class="stripe-card-data">

    <?php

    //include(dirname(__DIR__).DS.'lib'.DS.'omnipay'.DS.'cc_form_fields.php');

    ?>

    <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display Element errors. -->
    <div id="card-errors" role="alert"></div>

</div>
