<?php

namespace MicroweberPackages\TaxManager;

class TaxManager
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'cart_taxes';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = app();
        }
    }

    public function get($params = array())
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $table = $this->table;
        $params['table'] = $table;
        $get = $this->app->database_manager->get($params);

        return $get;
    }

    public function save($params = array())
    {
        if (isset($params['amount'])) {
            $params['amount'] = floatval($params['amount']);
        }

        $table = $this->table;
        $params['table'] = $table;
        $save = $this->app->database_manager->save($params);

        return $save;
    }

    public function delete_by_id($data)
    {
        if (!is_array($data)) {
            $id = intval($data);
            $data = array('id' => $id);
        }
        if (!isset($data['id']) or $data['id'] == 0) {
            return false;
        }
        $table = $this->table;
        $this->app->database_manager->delete_by_id($table, $id = $data['id'], $field_name = 'id');
    }

    public function calculate($sum, $is_gross = false)
    {
        $difference = 0;
        if ($sum > 0) {
            $taxes = $this->get('limit=1000');
            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if (isset($tax['id']) and isset($tax['tax_modifier']) and isset($tax['amount']) and $tax['amount'] != 0) {
                        $amt = floatval($tax['amount']);
                        if ($tax['tax_modifier'] == 'fixed') {
                            $difference = $difference + $amt;
                        } elseif ($tax['tax_modifier'] == 'percent') {
                            if($is_gross) {
								$difference_percent = $sum - (($sum / ($amt + 100)) * 100);
                            } else {
								$difference_percent = $sum * ($amt / 100);
                            }
                            // $difference_percent = round($difference_percent);
                            $difference = $difference + floatval($difference_percent);
                        }
                    }
                }
            }

            return $difference;
        }
    }
}
