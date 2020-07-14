<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\Shop\ClientsManager;

use MicroweberPackages\DatabaseManager\Crud;

class ClientsManager extends Crud
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'cart_clients';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = app();
        }
    }


    public function find_or_create_client_id($data)
    {

        $client = array();

        if (isset($data['email']) and $data['email']) {
            $client['email'] = $data['email'];
            $use_fake_mail = false;
        } else {
            $use_fake_mail = true;
           // $client['email'] = date("YmdHis").'@no-reply.local';
        }

        if (isset($data['first_name']) and $data['first_name']) {
            $client['first_name'] = $data['first_name'];
        }

        if (isset($data['last_name']) and $data['last_name']) {
            $client['last_name'] = $data['last_name'];
        }
        if (isset($data['user_id']) and $data['user_id']) {
            $client['user_id'] = $data['user_id'];
        }


        $related_data = new \Clients();


        if (isset($client['email']) ) {
            $related_data = $related_data->firstOrCreate([
                'email' => $client['email']
            ], [
                $client
            ]);
        } elseif (isset($client['user_id'])) {
            $related_data = $related_data->firstOrCreate([
                'id' => $client['user_id'],
            ], [
                $client
            ]);
        } elseif (isset($client['first_name']) and isset($client['last_name'])) {
            $related_data = $related_data->firstOrCreate([
                'first_name' => $client['first_name'],
                'last_name' => $client['last_name'],
            ], [
                $client
            ]);
        }

        if ($related_data and $related_data->id) {
            return $related_data->id;
        }


    }

}
