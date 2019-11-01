<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
// use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\State;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Jobs\EmailVerificationJob;
use App\Jobs\ResetPassWordTokenJob;
use App\Jobs\PasswordChangedJob;
use Auth, DB, Redirect, Hash;
use Carbon\Carbon;
use DateTime;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('jwt.auth', ["except" => ['create','resend_activation_link', 'send_reset_password_token', 'reset_password_with_token', 'activate_email_account']]);
    }

    
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::guard()->user();
        $user['state'] = State::where('id', $user->state_id)->select('id', 'state')->first();
        return response()->json($user);
    }

    /**
     * create a new specified resource
     * step one validation
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function create(Request $request)
    {
        return $data = $request->all();
        $validatedData = $request->validate([
            "email" => "required|email",
            "phone_number" => "required",
            "name" => "required",
            "role" => "required",
            "password" => "required"
        ]);

        return $store = self::store($data);
    }

    /**
     * save a new specified resource
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function store($data)
    {
        try {
            $store = new User;
            $store->email = $data['email'];
            $store->phone_number = $data['phone_number'];
            $store->surname = $data['name'];
            
            $store->password = bcrypt($data['password']);
            $store->role = $data['role'];
            $store->save();
            activity()
               ->causedBy($store)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('Account Created');
            return response()->json(["status" => "ok", "data" => $store, "msg" => "Account Created Successfully, Please click on the activation link sent to your email"]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * check if email resource exist.
     *
     * @param  \App\User  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function email_exist($email)
    {
        try { 
            $check = User::where('email', $email)->first();
            if($check){
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * get user email verified status
     *
     * @method user_email_verification_status
     * @param  \App\User  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function user_email_verification_status($email)
    {
        try {
            $user_status = User::where('email', $email)->select('id', 'email_verified')->first()->email_verified;
            return $user_status;
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * get user email verified status
     *
     * @method user_email_verification_status
     * @param  \App\User  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function resend_activation_link(Request $request)
    {
        $validatedData = $request->validate([
            "email" => "required",
        ]);
        $data = $request->all();
        try {
            // return User::where('email', $data['email'])->get();
            // return self::email_exist($data['email']);
            if(!self::email_exist($data['email']))
            {
                return response()->json(['status' => 'error', 'msg' => 'Oops!! this email does not exist on our system']);
            }
            if(self::user_email_verification_status($data['email']))
            {
                return response()->json(['status' => 'error', 'msg' => 'this email is already verified, please login. or change password if you\'ve forgotten']);
            }
            $user = User::where('email', $data['email'])->first();
            #send activation mail job
            $email_job = (new EmailVerificationJob($user))->delay(Carbon::now()->addSeconds(3));
            dispatch($email_job);
            return response()->json(['status' => 'ok', 'msg' => 'Operation successful, Please click on the activation link sent to your email']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * get user email verified status
     *
     * @method user_email_verification_status
     * @param  \App\User  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function activate_email_account($token)
    {
        $user_id =  \Crypt::decrypt($token);
        try {
            $user = User::where('id', $user_id)->first();
            if($user->email_verified)
            {
                $url = "https://rentch.ng/login/email-verified";
                return Redirect::to($url);
            }
            $user->email_verified = 1;
            $user->save();
            #send activation mail job
            //$email_job = (new EmailVerificationJob($user))->delay(Carbon::now()->addSeconds(3));
            //dispatch($email_job);
            $url = "https://rentch.ng/login/email-verified";
            return Redirect::to($url);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    

    /**
     * get user email verified status
     *
     * @method user_email_verification_status
     * @param  \App\User  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function send_reset_password_token(Request $request)
    {
        $validatedData = $request->validate([
            "email" => "required",
        ]);
        $data = $request->all();
        try {
            if(!self::email_exist($data['email']))
            {
                return response()->json(['status' => 'error', 'msg' => 'Oops!! this email does not exist on our system']);
            }
            $user = User::where('email', $data['email'])->first();
            #save token to reset_password table
            $token = rand(00000, 99999);
            $store = DB::table('password_resets')->insert(
                ['email' => $user->email, 'token' =>  $token, 'created_at' => Carbon::now() ]
            );
            #send activation mail job
            $reset_token_job = (new ResetPassWordTokenJob($user, $token))->delay(Carbon::now()->addSeconds(3));
            dispatch($reset_token_job);
            return response()->json(['status' => 'ok', 'msg' => 'Operation successful, Please a reset code (One Time Password) has been sent to your email']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function reset_password_with_token(Request $request)
    {
        // $validatedData = $request->validate([
        //     "email" => "required",
        //     "token" => "required",
        //     'password' => 'required',
        //     'password_confirm' => 'required',
        // ]);
        $data = $request->all();
        try {
            if(!self::email_exist($data['email']))
            {
                return response()->json(['status' => 'error', 'msg' => 'Oops!! this email does not exist on our system']);
            }
            //compare provided ton with hash token
            $reset_data = DB::table('password_resets')->where('email', $data['email'])->orderBy('created_at', 'desc')->first();

            if($reset_data->token == $data['token'])
            {
                $user = User::where('email', $data['email'])->first();
                #save token to reset_password table
        
                $user->password = Hash::make($data['password']);
                $user->save();
                
                #send password change notification mail job
                $change_password_job = (new PasswordChangedJob($user))->delay(Carbon::now()->addSeconds(3));
                dispatch($change_password_job);
                return response()->json(['status' => 'ok', 'msg' => 'Operation successful, password change successful, please login with your new password']);
            }else{
                return response()->json(['status' => 'error', 'msg' => 'Token not corrct']);
            }
            
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * @method update_password
     * update user password
     * @param $request
     * @return response
     */
    public static function update_password(Request $request)
    {
        $validatedData = $request->validate([
            "id" => "required",
            'password' => 'required',
            'password_confirm' => 'required',
        ]);
        $data = $request->all();
        try {
            $user = User::where('id', $data['id'])->select('id', 'password')->first();
            $user->password = bcrypt($data['password']);
            $user->save();
            
            #send password change notification mail job
            $change_password_job = (new PasswordChangedJob($user))->delay(Carbon::now()->addSeconds(3));
            dispatch($change_password_job);
            return response()->json(['status' => 'ok', 'msg' => 'Operation successful, password change successful, please login with your new password']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * update a new specified resource
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function update(Request $request)
    {
        $data = $request->all();
        try{
            $user = User::find(Auth::user()->id);
            if(!empty($data['name']))
            {
                $user->name = $data['name'];
            }
            // }
            if(!empty($data['phone_number']))
            {
                $user->phone_number = $data['phone_number'];
            }
            if(!empty($data['state_id']))
            {
                $user->state_id = $data['state_id'];
            }
            if(!empty($data['role']))
            {
                $user->role = $data['role'];
            }
            if(!empty($data['current_address']))
            {
                $user->current_address = $data['current_address'];
            }
            if(!empty($data['local_govt_id']))
            {
                $user->local_govt_id = $data['local_govt_id'];
            }
            if(!empty($data['marital_status']))
            {
                $user->marital_status = $data['marital_status'];
            }
            if(!empty($data['gender']))
            {
                $user->gender = $data['gender'];
            }
            if(!empty($data['age_range']))
            {
                $user->age_range = $data['age_range'];
            }
            if(!empty($data['title']))
            {
                $user->title = $data['title'];
            }
            if(!empty($data['occupation']))
            {
                $user->occupation = $data['occupation'];
            }
            if(!empty($data['profile_open_status']))
            {
                $user->profile_open_status = $data['profile_open_status'];
            }
            if(!empty($data['monthly_budget']))
            {
                $user->monthly_budget = $data['monthly_budget'];
            }
            if(!empty($data['description']))
            {
                $user->description = $data['description'];
            }
            if(!empty($data['facebook']))
            {
                $user->facebook = $data['facebook'];
            }
            if(!empty($data['twitter']))
            {
                $user->twitter = $data['twitter'];
            }
            if(!empty($data['instagram']))
            {
                $user->instagram = $data['instagram'];
            }
            if(!empty($data['linkedin']))
            {
                $user->linkedin = $data['linkedin'];
            }
            if(!empty($data['marital_status']))
            {
                $user->marital_status = $data['marital_status'];
            }
            $user->save();
                return response()->json([
                    'status' => 'ok',
                    'data'=> $user, 
                    'msg'=>'Profile Updated successfully'
                ]);
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * upload user image/avata
     *
     * @method upload_user_image
     * @param  \App\User  $request
     * @return \Illuminate\Http\Response
     */
    public static function upload_user_image(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                'user_id' => 'required',
            ]);

            if ($request->hasFile('avatar')) {
                
                /*$image = $request->file('avatar');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');*/
                $path = $request->file('avatar')->store('avatars');
                // $image->move($destinationPath, $name);
                $user = User::where('id', $request->input('user_id'))->select('id', 'avatar')->first();
                if($user->avatar){
                    #delete old avata | img 
                   self::delete_user_image($user->avatar);
                }
                $user->avatar = $path;
                $user->save();
                activity()
               ->causedBy($user)
               ->performedOn($user)
               ->withProperties(['id' => $user->id])
               ->log('User avatar updated');
                return response()->json(['status' => 'ok','data'=> $user, 'msg'=>'Image Upload successfully']);
            }
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * delete user image/avata
     *
     * @method upload_user_image
     * @param  \App\User  $request
     * @return \Illuminate\Http\Response
     */
    public static function delete_user_image($avata_path)
    {
         Storage::delete($avata_path);
    }
}
