<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    //

    protected $table = "chat_rooms";
    protected $primaryKey = 'id';
    protected $fillable = ['room_id', 'last_message', 'last_user', 'created_at', 'updated_at'];
}
