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
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'data' => [
                'email' => $this->email,
                'is_verified' => $this->is_verified,
                'is_active' => $this->is_active,
                'id' => $this->id,
            ],
            'message' => 'You have registered successfully'
        ];
    }
}