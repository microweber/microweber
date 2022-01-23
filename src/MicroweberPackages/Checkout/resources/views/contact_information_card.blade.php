<div class="card my-3">
    <div class="card-body d-flex p-3">

        <div class="col-8">
            <?php if (is_logged()):  $user = get_user();  ?>
            <?php _e("You are logged as"); ?> <?php echo $user['username']; ?>
                <br><br>
            <?php endif; ?>

            <?php if (empty($checkout_session['email']) || empty($checkout_session['email'])):  ?>
                         <?php _e("No personal information for order"); ?>
                        <?php endif;  ?>

            <?php if (!empty($checkout_session['first_name'])) { echo $checkout_session['first_name']; } ?>
            <?php if (!empty($checkout_session['last_name'])) { echo $checkout_session['last_name'] . '<br />'; } ?>
            <?php if (!empty($checkout_session['phone'])) { echo $checkout_session['phone'] . '<br />'; } ?>
            <?php if (!empty($checkout_session['email'])) { echo $checkout_session['email'] . '<br />'; } ?>
        </div>

        <div class="col-4 justify-content-end text-end text-right align-self-top px-0">
            <a href="{{ route('checkout.contact_information') }}" class="btn btn-link px-0">{{ _e('Edit') }}</a>
        </div>

    </div>
</div>
