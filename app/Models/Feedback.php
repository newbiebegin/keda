<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';
	protected $primaryKey = 'id';
	protected $fillable = ['informer_id', 'status', 'message', 'is_feedback', 'customer_id'];

    public function informer()
	{
		return $this->belongsTo(User::Class, 'informer_id');
	}

    public function customer()
	{
		return $this->belongsTo(User::Class, 'customer_id');
	}
}
