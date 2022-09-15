<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'informer_id' => $this->informer_id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'is_feedback' => $this->is_feedback,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'sender' => new UserResource($this->whenLoaded('sender')),
        
        ];
    }
}
