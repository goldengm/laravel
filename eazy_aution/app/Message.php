<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $primaryKey = 'id';
    protected $fillable = ['room_id', 'sender', 'content', 'is_read', 'sent_at'];
}
