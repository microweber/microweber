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
    <?php $rand = uniqid(); ?>
    <?php $chart_id = 'js_sales_stats_' . $rand; ?>
    <script type="text/javascript">
        window.onload = function () {
            Livewire.on('initSalesChart', () => {
                // Code Here
                initJsSalesChart<?php print $chart_id ?>()
            })
        }
    </script>
    <div class="card py-3 mb-3">
        <div class="card-body py-3">
            <div id="sales<?php print $chart_id ?>">

            </div>
            <div id="amount<?php print $chart_id ?>">

            </div>
            {!! json_encode($filter, JSON_PRETTY_PRINT) !!}

            <pre>
                {!! print_r($data, JSON_PRETTY_PRINT) !!}
           </pre>

            <?php
            $sales_numbers = [];
            $sales_numbers_amount = [];
            if (isset($data['orders_data'])) {
                $orders_data = $data['orders_data'];
                foreach ($orders_data as $item) {
                    $sales_numbers[$item['date']] = $item['count'];
                    $sales_numbers_amount[$item['date']] = $item['amount_rounded'];
                }
            }

            ?>

            <script>

                function initJsSalesChart<?php print $chart_id ?>() {
                    var chartEl = document.getElementById("sales<?php print $chart_id ?>");
                    if (typeof chartEl !== 'undefined' && chartEl !== null) {
                        if ($(chartEl).hasClass('chart-js-render-ready')) {
                            return;
                        }
                    }
                    $(chartEl).addClass('chart-js-render-ready')
                    var optionsSales = {
                        chart: {
                            id: 'chartSales',
                            type: 'line',
                            stacked: false
                        },
                        series: [
                            {
                                name: 'sales',
                                type: 'line',
                                data: <?php print json_encode(array_values($sales_numbers)) ?>
                            }
                        ],
                        xaxis: {
                            type: 'datetime',
                            tooltip: {
                                enabled: true
                            },
                            categories: <?php print json_encode(array_keys($sales_numbers)) ?>
                        }
                    }

                    var chart = new ApexCharts(chartEl, optionsSales);

                    chart.render();


                    var chartElAmount = document.getElementById("amount<?php print $chart_id ?>");


                    var optionsAmount = {
                        chart: {
                            id: 'chartAmount',
                            height: 130,
                            type: 'area',
                            brush: {
                                target: 'chartSales',
                                enabled: true
                            }
                        },
                        dataLabels: {
                            enabled: true,
                        },
                        series: [
                            {
                                name: 'amount',

                                data: <?php print json_encode(array_values($sales_numbers_amount)) ?>
                            }
                        ],
                        xaxis: {
                            type: 'datetime',
                            tooltip: {
                                enabled: true
                            },
                            categories: <?php print json_encode(array_keys($sales_numbers)) ?>
                        }
                    }

                    var chart = new ApexCharts(chartElAmount, optionsAmount);

                    chart.render();
                }

                initJsSalesChart<?php print $chart_id ?>()
            </script>
        </div>
    </div>

</div>














