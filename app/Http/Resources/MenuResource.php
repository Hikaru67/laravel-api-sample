<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'title' => $this->title,
            'link' => $this->link,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'menus' => self::collection($this->whenLoaded('menus')),
        ];
    }
}
