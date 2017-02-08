<?php

namespace Microweber\Install;

use Illuminate\Support\Facades\DB;
use Option;
use Shipping;

class DefaultOptionsInstaller
{
    public function run()
    {
        try {
            $this->setDefault();
            $this->setCommentsEnabled();
            $this->setShippingEnabled();
            $this->setPaymentsEnabled();
        } catch (\PDOException $e) {
            return false;
        }
        return true;
    }

    public function setDefault()
    {
        $existing = DB::table('options')->where('option_key', 'website_title')
            ->where('option_group', 'website')->first();
        if ($existing == false) {
            $option = new Option();
            $option->option_key = 'website_title';
            $option->option_group = 'website';
            $option->option_value = 'Microweber';
            $option->is_system = 1;
            $option->save();
        }

    }

    public function setCommentsEnabled()
    {
        $existing = DB::table('options')->where('option_key', 'enable_comments')
            ->where('option_group', 'comments')->count();

        if ($existing == false) {
            $save = array(
                'option_key' => 'enable_comments',
                'option_group' => 'comments',
                'option_value' => 'y'
            );
            $engine = mw()->database_manager->get_sql_engine();
            if ($engine == 'pgsql') {
                // PQSQL has error Unique violation: 7 ERROR: duplicate key value violates unique constraint .... :(
                $highestId = DB::table('options')->select(DB::raw('MAX(id)'))->first();
                $save['id'] = $highestId->max + 1;
            }
            DB::table('options')->insert($save);
        }
    }

    public function setShippingEnabled()
    {
        $existing = DB::table('options')->where('option_key', 'shipping_gw_shop/shipping/gateways/country')
            ->where('option_group', 'shipping')->first();
        if ($existing == false) {
            $save = array(
                'option_key' => 'shipping_gw_shop/shipping/gateways/country',
                'option_group' => 'shipping',
                'option_value' => 'y'
            );
            $engine = mw()->database_manager->get_sql_engine();
            if ($engine == 'pgsql') {
                $highestId = DB::table('options')->select(DB::raw('MAX(id)'))->first();
                $save['id'] = $highestId->max + 1;
            }
            DB::table('options')->insert($save);
        }


        $existing = DB::table('cart_shipping')->where('shipping_country', 'Worldwide')->first();

        if ($existing == false) {
            $save = array(
                'shipping_country' => 'Worldwide',
                'shipping_type' => 'fixed',
                'is_active' => 1,
                'shipping_cost' => 0
            );
            $engine = mw()->database_manager->get_sql_engine();
            if ($engine == 'pgsql') {
                $highestId = DB::table('cart_shipping')->select(DB::raw('MAX(id)'))->first();
                $save['id'] = $highestId->max + 1;
            }
            DB::table('cart_shipping')->insert($save);
        }

    }

    public function setPaymentsEnabled()
    {


        $existing = DB::table('options')->where('option_key', 'payment_gw_shop/payments/gateways/paypal')
            ->where('option_group', 'payments')->first();


        if ($existing == false) {
            $save = array(
                'option_key' => 'payment_gw_shop/payments/gateways/paypal',
                'option_group' => 'payments',
                'option_value' => 1
            );
            $engine = mw()->database_manager->get_sql_engine();
            if ($engine == 'pgsql') {
                $highestId = DB::table('options')->select(DB::raw('MAX(id)'))->first();
                $save['id'] = $highestId->max + 1;
            }
            DB::table('options')->insert($save);
        }


        $existing = DB::table('options')->where('option_key', 'currency')
            ->where('option_group', 'payments')->first();


        if ($existing == false) {
            $save = array(
                'option_key' => 'currency',
                'option_group' => 'payments',
                'option_value' => 'USD'
            );
            $engine = mw()->database_manager->get_sql_engine();
            if ($engine == 'pgsql') {
                $highestId = DB::table('options')->select(DB::raw('MAX(id)'))->first();
                $save['id'] = $highestId->max + 1;
            }
            DB::table('options')->insert($save);
        }

    }
}
