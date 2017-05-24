<?php

/*

type: layout

name: Default

description: Default discounts template

*/

?>
<script>
    mw.lib.require('bootstrap3ns');
</script>
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