<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'address' => $this->address,
            'menus' => $this->menus,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'access_token' => $this->when($this->whenLoaded($this->access_token), $this->access_token),
            'refresh_token' => $this->when($this->whenLoaded($this->refresh_token), $this->refresh_token),
        ];
    }
}
