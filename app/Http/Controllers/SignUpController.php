<?php

namespace App\Http\Controllers;

use Config;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
#jobs
use App\Jobs\EmailVerificationJob;

class SignUpController extends Controller
{
    public function signUp(Request $request)
    {
        if(UserController::email_exist($request->input('email')))
        {
            if(!UserController::user_email_verification_status($request->input('email')))
            {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Account not verified, please click on the activation link sent to your email'
                ], 201);
            }
            return response()->json([
                'status' => 'error',
                'msg' => 'Account already exist, please reset password if you cant remember your password'
            ], 201);
        }
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }
        activity()
               ->causedBy($user)
               ->performedOn($user)
               ->withProperties(['id' => $user->id])
               ->log('Account Created');
        
        $email_job = (new EmailVerificationJob($user))->delay(Carbon::now()->addSeconds(3));
        dispatch($email_job);

        return response()->json([
            'status' => 'ok',
            'data' => $user,
            "msg" => "Account Created Successfully, Please click on the activation link sent to your email"
        ], 200);
    }
}
