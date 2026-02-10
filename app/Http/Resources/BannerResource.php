<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerResource extends JsonResource
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'image_url' => $this->image_path ? Storage::url($this->image_path) : null,
            'target_url' => $this->target_url,
            'link_text' => $this->link_text,
            'placement' => $this->placement,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            // 'embed_code' => app(\App\Actions\GenerateEmbedCode::class)->execute($this->resource),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
