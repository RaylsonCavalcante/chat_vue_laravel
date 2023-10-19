<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    
    //Send Message
    public function sendMessage(Request $req){

        $message = new Message();

        $message->message = $req->msg;
        $message->user_id = $req->idUser;
        $message->for = $req->idS;
        $message->read = 1;
        $message->new = 1;

        $message->save();

        $var = 1;

        $arr = [$var];

        echo json_encode($arr);

    }

    //Show Messages
    public function showMessages(Request $req){

        $userFor = User::find($req->id);
        $photoFor = $userFor->photo;

        $message = Message::all();

        $arr = [$message, $photoFor];

        echo json_encode($arr);

    }

    //Check New Message
    public function newMessage(){
        
        $message = Message::where('new', 1)->first();

        if ($message) {
         
            $var = 1;

            $arr = [$var];

            echo json_encode($arr);
        }else{

            $var = 0;

            $arr = [$var];

            echo json_encode($arr);
        }

    }

    //Update Refresh
    public function updateRefresh(Request $req){
        
        $message = Message::where('new', 1)->get();

        if (count($message) > 0) {

            foreach ($message as $messages) {
                if($messages->for == $req->idUser){
                    $messages->new = 0;
                }
            }

            $messages->update();
        }

    }

    //Read Message
    public function readMessage(Request $req){
        
        $idUser = $req->idUser;
        $idS = $req->idS;

        $message = Message::all();

        if (count($message) > 0) {
            $count = 0;
            foreach ($message as $messages) {
                
                if($messages->user_id == $idS && $messages->for == $idUser){
                    if($messages->read == 1){
                        $messages->read = 0;
                        $messages->update();
                        $count = $count+1;
                    }
                }
            }

            $var = $count;
            $arr = [$var];

            echo json_encode($arr);
        }

    }
}
