<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Controllers\UserController;
use App\Jobs\EmailVerificationJob;
use Carbon\Carbon;
use App\User;

use Auth;

class LoginController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        $validatedData = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $data = $request->all();
                
            if(!User::where('email', $data['email'])->exists()){
                return response()->json(["success" => 0, "message" => "Incorrect Email or Email does not exit"], 200);
            }
            
            // $credentials = request(['email', 'password']);

            try {
                if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                   $user = Auth::user(); 
                   $token =  $user->createToken('AppName')->accessToken; 
                    return response()->json(['status' => 'ok', 'token' => $token, 'user' => $user, 'msg'=>'Authentication Successfull'], $this->successStatus); 
                  } else{ 
                   return response()->json(['status' => 'error', 'msg'=>'Incorrect Password'], 201); 
                   } 
                //$success['token'] =  $user->createToken('AppName')->accessToken;
                // if (!$token = JWTAuth::attempt($credentials)) {
                //     return response()->json(["success" => 0, "message" => 'Incorrect Password'], 200);
                // }
            } catch (Exception $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

    }
    
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
