<?php

namespace App\Api\V1\Controllers;

use App\EmailList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "email" => "required|email"
        ]);
        #check if email is unique
        if(self::email_exist($data['email']))
        {
            return response()
            ->json([
                'status' => 'error',
                'msg' => 'Subscribed Already'
            ]);
        }
        #save new resource
        $store = self::store($data);
        return response()
            ->json([
                'status' => 'ok',
                'data' => $store,
                'msg' => 'Email Subscription Successful'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($data)
    {
        try {
            $store = new EmailList;
            $store->email = $data['email'];
            $store->subscription_status = 1;
            $store->save();
            return $store;
        } catch (Exception $e) {
            return $r->getMessage();
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailList  $emailList
     * @return \Illuminate\Http\Response
     */
    public function show(EmailList $emailList)
    {
        $email_list = EmailList::get();
        return response()->json(['status'=>'ok', 'data'=> $email_list]);
    }
    /**
     * check if email resource exist.
     *
     * @param  \App\EmailList  $emailList
     * @return \Illuminate\Http\Response
     */
    public static function email_exist($email)
    {
        try {
            if(EmailList::where('email', $email)->exists()){
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailList  $emailList
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailList $emailList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailList  $emailList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailList $emailList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailList  $emailList
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailList $emailList)
    {
        //
    }
}
