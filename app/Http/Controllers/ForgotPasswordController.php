<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordEmail;
use Illuminate\Http\Request;
use App\Models\ForgotPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    //
    public function sendForgotPasswordEmail(Request $request)
    {
        $user= User::where("email",$request->email)->first();

        $expiry= Carbon::now()->addMinutes(5);

        ForgotPassword::updateOrCreate([
            "user_id"=>$user->id,
            "token"=>random_int(1000,9999),
            "expiry_date"=> $expiry
        ]);

        Mail::to($user->email)->send(new ResetPasswordEmail($user));

        $token = $user->createToken("LaravelAuthApp")->plainTextToken;

        return response()->json([
            "token"=>$token,
            "status"=> "success",
            "message"=> "Check your inbox for password reset mail",
        ],200);
    }

    public function resetPassword(Request $request)
    {
        $password = $request->input("password");
        $token = $request->input("token");
        
        $this->validate($request, [
            "password"=> "required",
            "token" => "required"
        ]);

        $getUser = ForgotPassword::where("token", $request->token)->first();

        if(!$getUser)
        {
            return response()->json([
                "message"=> "Incorrect Token"
            ], 400);
        }

        $currentDate =Carbon::now();

        if($getUser->expiry_date < $currentDate)
        {
            $expiry= Carbon::now()->addMinutes(5);

            ForgotPassword::updateOrCreate([
                "user_id"=>$getUser->user_id,
                "token"=>random_int(1000,9999),
                "expiry_date"=> $expiry
            ]);
            $user= User::where("id",$getUser->user_id)->first();

            Mail::to($user->email)->send(new ResetPasswordEmail($user));

            return response()->json([
                "message"=>"token expired, check your mail for another token"
            ]);

        }
        if($getUser){
            // $user= User::update("password",$password);
            $user= User::where("id",$getUser->user_id)->first();

            $user->password = $password;
            $user->save();

             ForgotPassword::where("user_id", $user->id)->delete();

            $token = $user->createToken("LaravelAuthApp")->plainTextToken;

            return response()->json([
                "token"=> $token,
                "message"=> "Password reset successful"
            ],200);
    
        }else{

            return response()->json([
            "message"=> "invalid token",
            ], 400);
        
        }



    }
}
