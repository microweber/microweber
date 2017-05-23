<?php

/*

type: layout

name: Default

description: Default discounts template

*/

?>
<!--<script>mw.moduleCSS("<?php print $config['url_to_module'] ?>//templates/templates.css", true);</script>-->
<script>
    mw.lib.require('bootstrap3ns');
</script>
<style>
    .mw-discounts-module input {
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        border: 1px solid #cdcdcd;
        color: #636363;
        font-size: 14px;
        box-shadow: none;
        width: 100%;
        padding: 23px 10px;
    }

    .mw-discounts-module button,
    .mw-discounts-module .btn-default,
    .mw-discounts-module a{
        width: 100%;
        padding: 14px 5px 12px 5px;
    }
</style>
<div class="bootstrap3ns">
    <div class="mw-discounts-module">
        <div class="row">
            <div class="col-xs-12">
                <h4>Enter promo code</h4>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <input type="text" name="promocode" class="form-control" placeholder="Enter promo code"/>
            </div>
            <div class="col-xs-6 right">
                <button type="button" class="btn btn-default">Apply code</button>
            </div>
        </div>
    </div>
</div>