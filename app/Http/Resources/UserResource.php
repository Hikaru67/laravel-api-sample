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
            'name' => $this->name,
            'menus' => $this->menus,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'api_token' => $this->when($this->whenLoaded($this->api_token), $this->api_token),
        ];
    }
}
