<?php

namespace mw_payments;

use Hash;

class Key
{
    private $table_payment_keys = 'mw_payment_keys';

    public function __construct()
    {

    }


    function generate_public_key()
    {
        return user_id() . '|' . str_random(60) . rand();;
    }

    function generate_private_key()
    {
        return user_id() . '|' . str_random(123) . rand();
    }

    public function save($params)
    {
        if (!is_array($params)) {
            return;
        }
        $params['table'] = $this->table_payment_keys;

        if (isset($params['public_key'])) {
            $check = array();
            $check['public_key'] = $params['public_key'];

            $check['single'] = true;
            $check = $this->get($check);

            if (isset($check['id'])) {
              //  return array('error' => "Key is taken");
            }
            $check['public_key'] = $this->generate_public_key();
            $check['private_key'] = $this->generate_private_key();
        }

        if (isset($params['id'])) {
            $check = array();
            $check['id'] = $params['id'];
            $check['single'] = true;
            $check = $this->get($check);
            if (!isset($check['created_by']) or $check['created_by'] != user_id()) {
                return array('error' => "ID is invalid");

                return false;
            }
        }
        return mw()->database_manager->save($params);
    }
    public function get_owner_keys($params = array())
    {
        $params['created_by'] = user_id();
        return $this->get($params);
    }

    public function get($params)
    {
        if (!is_array($params)) {
            return;
        }
        $params['table'] = $this->table_payment_keys;

        return mw()->database_manager->get($params);
    }
}
