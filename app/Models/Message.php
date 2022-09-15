<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
	protected $primaryKey = 'id';
	protected $fillable = ['sender_id', 'recipient_id', 'message', 'sent_date'];

    
    public function sender()
	{
		return $this->belongsTo(User::Class, 'sender_id');
	}

    public function recipient()
	{
		return $this->belongsTo(User::Class, 'recipient_id');
	}
}
