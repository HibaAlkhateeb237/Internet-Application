<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'user_name' => $this->user ? $this->user->name : null,
            'agency_name' => $this->agency ? $this->agency->name : null,
            'reference_number' => $this->reference_number,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'status' => $this->status,
            'locked_by_admin_id' => $this->locked_by_admin_id,
            'locked_at' => $this->locked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
