<?php


namespace MicroweberPackages\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toAzxdfsdfxxrray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'contact_no' => $this->contact_no,
            'birthday' => $this->birthday,
            'username' => $this->username,
            'email' => $this->email,
            'question' => $this->question_id,
            'security_answer' => $this->security_answer
        ];
    }
}



