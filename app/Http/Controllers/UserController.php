<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\VerifyEmail;
use App\Models\VerifyUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $this->validate($request, [
            "name"=> "required",
            "phone"=>"numeric|required",
            "email"=>"required|email",
            "password"=> "required",
        ]);

        // dd($request->all());

        $user = User::create([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "email"=>$request->email,
            "password"=>$request->password
        ]);

        $expiry= Carbon::now()->addMinutes(5);

        VerifyUser::updateOrCreate([
            "user_id"=>$user->id,
            "token"=>random_int(1000,9999),
            "expiry_date"=> $expiry
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        $token = $user->createToken("LaravelAuthApp")->plainTextToken;


        return response()->json([
            "data"=>$user,
            "token"=>$token,
            "status"=> "success",
            "message"=> "Registration successful, check your inbox to verify your email",
        ],200);
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            "email"=> "email|required",
            "password"=> "required",
        ]);

        $user = User::where("email",$request->email)->first();
        $token = $user->createToken("LaravelAuthApp")->plainTextToken;

        if(Hash::check($request->password,$user->password)){
              return response()->json([
                "data" => $user,
                "token"=> $token,
                "status"=> "success",
                "message"=> "you have successfully log in"
              ],200);
        }else{
            return response()->json(["error"=> "Credentials does not match our records"],400);
        }

    }

    public function verifyUser(Request $request) {
        $token = $request->input('token'); 

        $validate =   $this->validate($request, [
           "token" => "required"
        ]);

        $verifyUser = VerifyUser::where("token", $validate['token'])->first();

        $currentDate =Carbon::now();

        if($verifyUser->expiry_date < $currentDate)
        {
             $expiry= Carbon::now()->addMinutes(5);

            VerifyUser::updateOrCreate([
                "user_id"=>$verifyUser->user_id,
                "token"=>random_int(1000,9999),
                "expiry_date"=> $expiry
            ]);
            $user= User::where("id",$verifyUser->user_id)->first();

            Mail::to($user->email)->send(new VerifyEmail($user));

            return response()->json([
                "message"=>"token expired, check your mail for another token"
            ]);

        }
        if($verifyUser){
            $user= User::where("id",$verifyUser->user_id)->first();

            $user->email_verified_at= now();
            $user->save();

             VerifyUser::where("user_id", $user->id)->delete();

              $token = $user->createToken("LaravelAuthApp")->plainTextToken;

              return response()->json([
                "token"=> $token,
                "message"=> "user verified successfully"
              ],200);
    
        }else{

            return response()->json([
            "message"=> "invalid token",
            ], 400);
        
        }   
    }

    public function getSingleUser($id)
    {
        // $user = Auth::user();
        
        $user = User::where("id",$id)->first();
        // dd($user);

        if(!$user){
            return response()->json(["message" => "User Does Not Exist"], 400);
        }
            return response()->json([
            "data" => $user,
            "status"=> "ok"
        ],200);
           
    }

    public function getAllUsers()
    {
        // $users = User::where('status', 'active')->get();
        $users = User::all();
        return response()->json([
            "data" => $users,
            "status" => "success",
        ],200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            "name"=> "nullable",
            "phone"=>"nullable",
            "email"=>"nullable"
        ]);

        $updatedUser = User::where("id", $user->id)
                            ->update([
            "name"=> $request->name,
            "phone"=> $request->phone,
            "email"=> $request->email
        ]);

        return response()->json([
            'data' => $updatedUser,
            'message' => 'User record updated successfully',
        ], 200);
    }
}
