<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/11/2020
 * Time: 10:32 AM
 */

namespace MicroweberPackages\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct($resource, $attribute)
    {
        $this->resource = $resource;
        $this->attribute = $attribute;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resp = [];
        if(is_object($this) and isset($this->email)){
            $resp['email'] = $this->email;
        }

        if(is_object($this) and isset($this->is_verified)){
            $resp['is_verified'] = $this->is_verified;
        }

        if(is_object($this) and isset($this->is_active)){
            $resp['is_active'] = $this->is_active;
        }

        if(is_object($this) and isset($this->id)){
            $resp['id'] = $this->id;
        }

        $all_resp= [
            'success' => true,
            'data' => $resp,
        ];


        if(!empty($this->attribute['redirect'])){
            $all_resp['redirect'] = $this->attribute['redirect'];
        }

        if(!empty($this->attribute['success'])){
            $all_resp['message'] = $this->attribute['success'];
        }

        return $all_resp;
    }
}