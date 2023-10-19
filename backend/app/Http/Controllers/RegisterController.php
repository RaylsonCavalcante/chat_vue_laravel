<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Str;

class RegisterController extends Controller
{   

    //Register
    public function register(Request $req)
    {
        $queryUser = User::where('email',$req->email)->first();

        if ($queryUser) {
            return response()->json(['success'=>true, 'var' => 0], 200);
        }else{

            $user = new User;

            $pass_crypt = bcrypt($req->password);

            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = $pass_crypt;
            $user->email_verified_at = now();
            $user->status = 0;
            $user->remember_token = Str::random(40);

            if ($req->hasFile('fileProfile') && $req->file('fileProfile')->isValid()) {
                
                $reqImage = $req->fileProfile;

                $extension = $reqImage->extension();

                $imageName = md5($reqImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

                $reqImage->move(public_path('img/profile'), $imageName);

                $user->photo = $imageName;
            }

            $user->save();

            return response()->json(['success'=>true, 'var' => 1], 200);
        }
    }

    //Data User
    public function dataUser(Request $req){

        $user = User::find($req->idUser);

        $urlPhoto = url("img/profile/{$user->photo}");

        return response()->json(['photo'=>$urlPhoto, 'name' => $user->name, 'email' => $user->email], 200);

    }

    //Update User
    public function updateUser(Request $req)
    {   
        $user = User::find($req->idUser);

        $user->name = $req->name;
        $user->email = $req->email;

        //Photo Upload
        if ($req->hasFile('photo') && $req->file('photo')->isValid()) {

            if($user->photo){
                unlink("img/profile/".$user->photo);
            }
            
            $reqImage = $req->photo;

            $extension = $reqImage->extension();

            $imageName = md5($reqImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $reqImage->move(public_path('img/profile'), $imageName);

            $user->photo = $imageName;
        }

        $user->update();

        return response()->json(['success'=>true, 'var' => 1], 200);

    }

    //Show Users
    public function showUsers(){

        $user =  User::orderBy('name')->get();
        $message = Message::all();

        $urlPhoto = url("img/profile/");

        $arr = [$user, $urlPhoto, $message];

        echo json_encode($arr);

    }
}
