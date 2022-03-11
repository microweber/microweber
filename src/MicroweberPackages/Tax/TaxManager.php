<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Tax;

use Illuminate\Support\Facades\Validator;
use MicroweberPackages\Helper\HTMLClean;

class TaxManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

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

        if (isset($params['id'])) {
            $findTax = TaxType::where('id', $params['id'])->first();
        } else {
            $findTax = TaxType::all();
        }

        if ($findTax) {
            return $findTax->toArray();
        }

        return false;
    }

    public function save($params = array())
    {
        if (isset($params['rate'])) {
            $params['rate'] = floatval($params['rate']);
        }

        $rules = [];
        $rules['name'] = 'required|max:500';
        $rules['type'] = 'required|max:500';
        $rules['rate'] = 'required|max:500';

        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            return ['valid'=>false,'errors'=>$errors];
        }

        $taxType = TaxType::where('id', $params['id'])->first();
        if (!$taxType) {
            $taxType = new TaxType();
        }

        $taxType->name = $params['name'];
        $taxType->type = $params['type'];
        $taxType->rate = $params['rate'];
        $taxType->description = '';

        if (isset($params['compound_tax'])) {
            $taxType->compound_tax = $params['compound_tax'];
        }

        return $taxType->save();
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

        $tax = TaxType::where('id', $data['id'])->first();
        return $tax->delete();
    }

    public function calculate($sum, $is_gross = false)
    {
        $difference = 0;
        if ($sum > 0) {

            $findTax = TaxType::all();
            if ($findTax) {
                $taxes = $findTax->toArray();
            }

            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if (isset($tax['id']) and isset($tax['type']) and isset($tax['rate']) and $tax['rate'] != 0) {
                        $amt = floatval($tax['rate']);
                        if ($tax['type'] == 'fixed') {
                            $difference = $difference + $amt;
                        } elseif ($tax['type'] == 'percent') {
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
