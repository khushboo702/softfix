<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'number' => $this->number,
            'image' => $this->image ? url('public/upload/profile_image') . '/' . $this->image : null,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'deleted_at' => (string) $this->deleted_at,
            'updated_at' => (string) $this->updated_at,
            'userPosts' => $this->PostData,

        ];
    }
}
