<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'start_at' => optional($this->created_at)->toDateTimeString(),
            'end_at' => optional($this->end_at)->toDateTimeString(),
            'basic_package_text' => $this->basic_package_text,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
