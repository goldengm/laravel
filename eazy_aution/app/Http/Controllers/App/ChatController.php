<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Message;
use App\ChatRooms;

use Mail;
use DB;
//for password encryption or hash protected
use Hash;
use DateTime;

class ChatController extends Controller
{
    public function sendMessage(Request $request) {
        $room_id = $request->input('room_id');
        $sender = $request->input('sender');
        $content = $request->input('content');
        $sent_at = date('Y-m-d h:i:s');
        $message = new Message();
        $message->room_id = $room_id;
        $message->sender = $sender;
        $message->content = $content;
        $message->sent_at = $sent_at;
        $message->save();
        event(new MessageSent($message, $room_id));
        return json_encode($message);
    }

    public function getAllMessages(Request $request) {
        $room_id = $request->input('room_id');
        $result = Message::where('room_id', $room_id)->get();
        if ($result) {
            return json_encode([
                "success" => true,
                "data" => $result->toArray()
            ]);
        }else{
            return json_encode([
                "success" => false,
                "message" => "No data found"
            ]);
        }

    }

    public function getChatRooms(Request $request) {
        $user_id = $request->input("user_id");
        $result = ChatRooms::where('room_id', 'LIKE', "%_{$user_id}")
                            ->orWhere("room_id, 'LIKE", "{$user_id}_%")->get();
        if ($result) {
            return json_encode([
                "success" => true,
                "data" => $result->toArray()
            ]);
        }else{
            return json_encode([
                "success" => false,
                "message" => "No data found"
            ]);
        }
    }
}
