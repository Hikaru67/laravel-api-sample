<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThesisResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'attaches' => $this->attaches,
            'student_id' => $this->student_id,
            'lecturer_id' => $this->lecturer_id,
            'student' => new StudentResource($this->whenLoaded('student')),
            'lecturer' => new LecturerResource($this->whenLoaded('lecturer')),
            'created_at' => $this->created_at,
        ];
    }
}
