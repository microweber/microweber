<div class="row">

    <div class="col-md-2">
        <module type="users/sidebar" />
    </div>

    <div class="col-md-8">

        <a href="<?php echo site_url('users/orders'); ?>" class="btn btn-primary btn-block">Back to orders</a>

        <h4>View Order #<?php echo $order['id']; ?></h4>

        <?php
        echo $order['first_name'];
        ?>

        <pre>
        <?php
            print_r($order);
        ?>
        </pre>

    </div>
</div>
