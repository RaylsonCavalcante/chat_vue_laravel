<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Message;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{   
    public function __construct(){
        $this->middleware('jwtauth')->except('login');
    }
    
    //Login
    public function login(Request $req)
    {   

        $credenciais = [
            'email' => $req->email,
            'password' => $req->password
        ];
        
        if ($token = JWTAuth::attempt($credenciais)) {
            $user = User::where('email', $req->email)->first();

            //Change status to online
            $user->status = 1;
            $user->update();

            return response()->json(['var' => 1,'idUser' => $user->id, 'access_token' => $token],200);

        }else{
            $var = 0;

            $arr = [$var];

            echo json_encode($arr);

        }
    }

    //Logout
    public function logout(){

        $user = Auth::user();
        $user->status = 0;
        if($user->update()){
            auth()->logout();

            return response()->json(['success'=>true, 'var' => 1], 200);
        }else{

            return response()->json(['success'=>true, 'var' => 2], 200);
        }
    }

    public function checkToken(){
        return response()->json(['success'=>true, 'var'=> 1],200);
    }

}
