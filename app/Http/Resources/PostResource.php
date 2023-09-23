<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => url('public/upload/post_image') . '/' . $this->image,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'deleted_at' => (string) $this->deleted_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
