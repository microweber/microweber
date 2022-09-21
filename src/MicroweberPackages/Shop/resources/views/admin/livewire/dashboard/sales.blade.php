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


    <?php

    $currency_display = '';
    $show_period_range = '';

    if (isset($filters['currency'])) {
        $currency_display = $filters['currency'];
    }

    if (isset($view['show_period_range'])) {
        $show_period_range = ucfirst($view['show_period_range']);
    }
    ?>

    <div class="card-body">
        {!! json_encode($filters, JSON_PRETTY_PRINT) !!}
        {!! json_encode($view, JSON_PRETTY_PRINT) !!}


        <label for="start_date">Start date:</label>

        <input type="date" id="start_date" wire:model="filters.from"/>

        <label for="end_date">End date:</label>

        <input type="date" id="end_date" wire:model="filters.to"/>


    </div>


    <?php if(isset($view['supported_currencies'])): ?>
    <div class="card-body">
        <?php foreach ($view['supported_currencies'] as $currency): ?>
        <?php
        $class = 'btn-outline-secondary';

        if (isset($filters['currency']) and $filters['currency'] == $currency['currency']) {
            $class = 'btn-primary';
        }
        ?>
        <button class="btn <?php print $class ?>"
                wire:click="changeCurrency('<?php print $currency['currency'] ?>')"><?php print $currency['currency'] ?></button>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <?php if(isset($view['supported_period_ranges'])): ?>
    <div class="card-body">
        <?php foreach ($view['supported_period_ranges'] as $supported_period_range): ?>
        <?php
        $class = 'btn-outline-secondary';

        if (isset($view['show_period_range']) and $view['show_period_range'] == $supported_period_range) {
            $class = 'btn-primary';
        }
        ?>
        <button class="btn <?php print $class ?>" wire:click="changePeriodDateRangeType('<?php print $supported_period_range ?>')" ><?php print $supported_period_range ?></button>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>










    <div class="card py-3 mb-3">
        <div class="card-body py-3">
            <div class="row g-0">


                <?php if(isset($data['orders_total_amount'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Amount (<?php print $currency_display ?>)</h6>
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
            <div class="row" id="sales<?php print $chart_id ?>">

            </div>
            <div class="row" id="amount<?php print $chart_id ?>">

            </div>

            <div class="row w-100" id="echart_sales_<?php print $chart_id ?>">

            </div>
            <div class="row w-100" id="echart_sales_amount_<?php print $chart_id ?>">

            </div>
            {!! json_encode($filters, JSON_PRETTY_PRINT) !!}

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


                    // echarts
                    var chartDom = document.getElementById('echart_sales_<?php print $chart_id ?>');
                    salesChart = echarts.init(chartDom,null, {
                     //   width: "100%",
                        height: 400
                    })

                    salesChartTooltipOptions = {
                        show: true,
                        position: 'top',
                      //  confine: true,
                        textStyle: {
                            overflow: 'breakAll',
                            width: 40,
                        },
                    };

                    salesChart.setOption({
                        legend: {},
                        tooltip: salesChartTooltipOptions,
                        xAxis: {
                            type: "category",
                            data: <?php print json_encode(array_keys($sales_numbers)) ?>
                        },
                        yAxis: {
                            type: "value"
                        },
                        series: [
                            {
                                name: '<?php print $show_period_range ?> Orders',
                                data: <?php print json_encode(array_values($sales_numbers)) ?>,
                                type: "line",
                                smooth:false,
                                lineStyle: {color: '#26be6b'}

                            }
                        ]
                    });



                    window.onresize = function() {
                        salesChart.resize();
                    };






                    // echarts sales amount
                     var chartDom = document.getElementById('echart_sales_amount_<?php print $chart_id ?>');


                      salesAmountChart = echarts.init(chartDom,null, {
                            //   width: "100%",
                            height: 400
                        })



                    salesAmountChart.setOption({
                        legend: {},
                        tooltip: salesChartTooltipOptions,
                        xAxis: {
                            type: "category",
                            data: <?php print json_encode(array_keys($sales_numbers)) ?>
                        },
                        yAxis: {
                            type: "value"
                        },
                        series: [
                            {
                                name: '<?php print $show_period_range ?> Sales (<?php print $currency_display ?>)',
                                data: <?php print json_encode(array_values($sales_numbers_amount)) ?>,
                                type: 'bar'
                            }
                        ]
                    });



                    window.onresize = function() {
                        salesAmountChart.resize();
                    };







                }

                initJsSalesChart<?php print $chart_id ?>()
            </script>
        </div>
    </div>

</div>














