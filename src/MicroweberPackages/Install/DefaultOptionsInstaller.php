<?php

namespace MicroweberPackages\Install;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Option\Models\Option;


class DefaultOptionsInstaller
{
    public function run()
    {

//        $this->setDefault();
//        $this->setCommentsEnabled();
//        $this->setShippingEnabled();
//        $this->setPaymentsEnabled();

        return true;
    }

    public function setDefault()
    {
        // AI-108 / TICKET-BG (cycle-133): routed through the Option model.
        $existing = Option::query()
            ->where('option_key', 'website_title')
            ->where('option_group', 'website')
            ->exists();
        if (!$existing) {
            $option = new Option();
            $option->option_key = 'website_title';
            $option->option_group = 'website';
            $option->option_value = 'Microweber';
            $option->is_system = 1;
            $option->save();
        }
    }

    public function setLanguage($language)
    {
    	$existing = Option::where('option_key', 'language')->where('option_group', 'website')->first();
    	if ($existing) {
            $existing->option_value = $language;
            $existing->save();
        } else {
    		$option = new Option();
    		$option->option_key = 'language';
    		$option->option_group = 'website';
    		$option->option_value = $language;
    		$option->is_system = 1;
    		$option->save();
    	}
    }

    public function setCommentsEnabled()
    {
        // AI-108 / TICKET-BG (cycle-133): routed through the Option model.
        // The pgsql sequence-collision workaround that the legacy raw insert
        // carried is no longer needed — Eloquent's save() goes through the
        // connection's native lastInsertId path which respects pgsql
        // sequence semantics correctly.
        $exists = Option::query()
            ->where('option_key', 'enable_comments')
            ->where('option_group', 'comments')
            ->exists();
        if (!$exists) {
            $option = new Option();
            $option->option_key = 'enable_comments';
            $option->option_group = 'comments';
            $option->option_value = 'y';
            $option->save();
        }
    }

    public function setShippingEnabled()
    {
        // AI-108 / TICKET-BG (cycle-133): routed through the Option model.
        $shippingExists = Option::query()
            ->where('option_key', 'shipping_gw_shop/shipping/gateways/country')
            ->where('option_group', 'shipping')
            ->exists();
        if (!$shippingExists) {
            $option = new Option();
            $option->option_key = 'shipping_gw_shop/shipping/gateways/country';
            $option->option_group = 'shipping';
            $option->option_value = 'y';
            $option->save();
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
        // AI-108 / TICKET-BG (cycle-133): routed through the Option model.
        $paypalExists = Option::query()
            ->where('option_key', 'payment_gw_shop/payments/gateways/paypal')
            ->where('option_group', 'payments')
            ->exists();
        if (!$paypalExists) {
            $option = new Option();
            $option->option_key = 'payment_gw_shop/payments/gateways/paypal';
            $option->option_group = 'payments';
            $option->option_value = 1;
            $option->save();
        }

        $currencyExists = Option::query()
            ->where('option_key', 'currency')
            ->where('option_group', 'payments')
            ->exists();
        if (!$currencyExists) {
            $option = new Option();
            $option->option_key = 'currency';
            $option->option_group = 'payments';
            $option->option_value = 'USD';
            $option->save();
        }
    }
}
