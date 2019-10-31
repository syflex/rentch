<?php

namespace App\Http\Controllers;

use App\UserPreference;
use Illuminate\Http\Request;

use Auth;
class UserPreferenceController extends Controller
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
            //"state_id" => "required",
            "user_id" => "required"
        ]);
        $preference = self::store($data);
        return response()->json(['status' => 'ok', 'data'=> $preference, 'msg' => 'Preference Updated successfully']);
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
            if(self::check_if_exist($data['user_id'])){
                $store = UserPreference::where('user_id', $data['user_id'])->first();
            }else{
                $store = new UserPreference;
                $store->user_id = $data['user_id'];
            }
            if(!empty($data['state_id']))
            {
                $store->state_id = $data['state_id'];
            }
            if(!empty($data['local_govt_id']))
            {
                $store->local_govt_id = $data['local_govt_id'] ?? null;
            }
            if(!empty($data['city_id']))
            {
                $store->city_id = $data['city_id'];
            }
            if(!empty($data['gender']))
            {
                $store->gender = $data['gender'];
            }
            if(!empty($data['accomation_preference']))
            {
                $store->accomation_preference = $data['accomation_preference'];
            }
            if(!empty($data['smoking']))
            {
                $store->smoking = $data['smoking'];
            }
            if(!empty($data['pets']))
            {
                $store->pets = $data['pets'];
            }

            if(!empty($data['sports']))
            {
                $store->sports = $data['sports'];
            }
            if(!empty($data['cooking_habits']))
            {
                $store->cooking_habits = $data['cooking_habits'];
            }
            if(!empty($data['party']))
            {
                $store->party = $data['party'];
            }
            if(!empty($data['social_life']))
            {
                $store->social_life = $data['social_life'];
            }
            if(!empty($data['late_nights']))
            {
                $store->late_nights = $data['late_nights'];
            }
            if(!empty($data['neatness']))
            {
                $store->neatness = $data['neatness'];
            }
            if(!empty($data['sharing_habits']))
            {
                $store->sharing_habits = $data['sharing_habits'];
            }
            if(!empty($data['guest']))
            {
                $store->guest = $data['guest'];
            }
            if(!empty($data['sickness_level']))
            {
                $store->sickness_level = $data['sickness_level'];
            }
            if(!empty($data['sports']))
            {
                $store->sports = $data['sports'];
            }
            if(!empty($data['hobbies']))
            {
                $store->hobbies = $data['hobbies'];
            }
            if(!empty($data['strugles']))
            {
                $store->strugles = $data['strugles'];
            }
            if(!empty($data['more_details']))
            {
                $store->more_details = $data['more_details'];
            }
            $store->save();
            /*activity()
               ->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('user preference updated');*/
            return $store; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPreference  $UserPreference
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        try {
            if(self::check_if_exist($user_id)){
                $preference = UserPreference::where('user_id', $user_id)->first();
                return response()->json(['status' => 'ok', 'data'=> $preference ]);
            }
            return response()->json(['status' => 'error', 'msg'=> 'no user preference yet' ]);
        } catch (Exception $e) {
            
        }
    }

    /**
     * check if resource already exist
     *
     * @param  \App\storeAmenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public static function check_if_exist($user_id)
    {
        try {
            if(UserPreference::where('user_id', $user_id)->exists())
            {
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPreference  $UserPreference
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPreference $UserPreference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPreference  $UserPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPreference $UserPreference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPreference  $UserPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        try {
            if(UserPreference::where('user_id',$user_id)->exists()){
                $preference = UserPreference::where('user_id', $user_id)->first();
                UserPreference::destroy($preference->id);
                activity()
                   ->causedBy(Auth::user()->id)
                   ->performedOn($preference)
                   ->withProperties(['id' => $preference->id])
                   ->log('user preference deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
}
