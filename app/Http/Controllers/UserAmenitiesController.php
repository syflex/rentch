<?php

namespace App\Http\Controllers;

use App\UserAmenities;
use App\Amenities;
use Illuminate\Http\Request;
use Auth;

class UserAmenitiesController extends Controller
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
            "user_id" => "required",
            "amenity_id" => "required"
        ]);
        if(self::check_if_exist($data))
        {
            return response()->json(['status'=> 'error', 'msg'=> 'already added']);
        }
        $amenity = self::store($data);
        return response()->json(['status' => 'ok', 'data'=> $amenity, 'msg' => 'Data added successfully']); 
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
            $store = new UserAmenities;
            $store->user_id = $data['user_id'];
            $store->amenity_id = $data['amenity_id'];
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('user amenity created');
            return $store; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * creating multiple  new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulk_update(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "user_id" => "required",
            "amenities" => "required"
        ]);
        if(!empty($data['amenities']))
        {
            $saved_array = [];
            foreach ($data['amenities'] as $key) {
                $new_data = [
                    'user_id' => $data['user_id'],
                    'amenity_id' => $key
                ];
                if(!self::check_if_exist($new_data))
                {
                    $saved = self::store($new_data);
                }
                array_push($saved_array, $new_data);
            }
            return $saved_array;
        }
        return response()->json(['status' => 'ok', 'data'=> '', 'msg' => 'Data added successfully']); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserAmenities  $userAmenities
     * @return \Illuminate\Http\Response
     */
    public function show(int $user_id)
    {
        try {
            $user_amenities = UserAmenities::where('user_id', $user_id)->get();
            /*$amenities = Amenities::get();
            $array = [];
            foreach ($amenities as $amenity) {
                foreach ($user_amenities as $user_amenity) {
                    if($amenity->id === $user_amenity->amenity_id)
                    {
                        $amenity->status = true;
                    }
                }
            }*/
            return response()->json(['status' => 'ok', 'data' => $user_amenities]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserAmenities  $userAmenities
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAmenities $userAmenities)
    {
        //
    }

    /**
     * check if resource already exist
     *
     * @param  \App\UserAmenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public static function check_if_exist($data)
    {
        try {
            if(UserAmenities::where('user_id', $data['user_id'])->where('amenity_id', $data['amenity_id'])->exists())
            {
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserAmenities  $userAmenities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAmenities $userAmenities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAmenities  $userAmenities
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAmenities $amenity_id)
    {
         try {
            if(UserAmenities::where('id',$amenity_id)->exists()){
                $amenity = UserAmenities::where('id', $amenity_id)->first();
                UserAmenities::destroy($amenity_id);
                activity()
                   ->causedBy(Auth::user())
                   ->performedOn($amenity)
                   ->withProperties(['id' => $amenity->id])
                   ->log('amenity deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
}
