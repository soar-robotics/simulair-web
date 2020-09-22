<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

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
            'id' => $this->id,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_verified' => $this->is_verified,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'profile_image' => $this->image_url,
            'company' => $this->company,
            'google_id' => $this->google_id,
            'google_avatar' => $this->google_avatar,
            'google_auth_at' => $this->google_auth_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
