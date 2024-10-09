<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {   
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'updatedAt' => $this->updated_at,
            'createdAt' => $this->created_at
        ];
    }
}
