<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Notification */
class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'data' => $this->data,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
