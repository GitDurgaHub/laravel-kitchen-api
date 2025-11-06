<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'items' => $this->items,
            'pickup_time' => optional($this->pickup_time)->toIso8601String(),
            'VIP' => (bool)$this->is_vip,
            'status' => $this->status,
            'completed_at' => optional($this->completed_at)->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}