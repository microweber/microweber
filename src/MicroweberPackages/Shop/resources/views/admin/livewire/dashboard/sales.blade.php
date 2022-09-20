<?php

?>
 

<div class="card" wire:init="loadSalesData">

    <div wire:loading>

        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="card-body">

        <?php foreach ($supported_currencies as $currency): ?>
        <?php
        $class = 'btn-outline-secondary';

        if (isset($filter['currency']) and $filter['currency'] == $currency['currency']) {
            $class = 'btn-primary';
        }
        ?>
        <button class="btn <?php print $class ?>"
                wire:click="changeCurrency('<?php print $currency['currency'] ?>')"><?php print $currency['currency'] ?></button>
        <?php endforeach; ?>


        {!! json_encode($filter, JSON_PRETTY_PRINT) !!}
        <pre>
             {!! print_r($data, JSON_PRETTY_PRINT) !!}
        </pre>

    </div>

    <div class="card py-3 mb-3">
        <div class="card-body py-3">
            <div class="row g-0">


                <?php if(isset($data['orders_total_amount'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Amount </h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print currency_format($data['orders_total_amount'], $currency); ?> </p>
                </div>
                <?php endif; ?>

                <?php if(isset($data['orders_total_count'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Orders count </h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print $data['orders_total_count']; ?> </p>
                </div>
                <?php endif; ?>
                <?php if(isset($data['orders_total_items_count'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Products sold </h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print $data['orders_total_items_count']; ?> </p>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
