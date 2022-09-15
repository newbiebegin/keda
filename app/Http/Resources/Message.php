<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            'sender_id' => $this->sender_id,
            'recipient_id' => $this->recipient_id,
            'sent_date' => $this->sent_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'sender' => new UserResource($this->whenLoaded('sender')),
        
        ];
    }
}
