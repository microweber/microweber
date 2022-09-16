<?php

namespace MicroweberPackages\Order\Http\Livewire\Admin\Traits;

trait WithColumnsManager
{
    public $showColumns = [
        'id' => true,
        'products' => true,
        'customer' => true,
        'total_amount' => true,
        'shipping_method' => true,
        'payment_method' => true,
        'payment_status' => true,
        'status' => true,
        'created_at' => false,
        'updated_at' => false,
        'actions' => true
    ];
}
