<?php

namespace App\Http\Controllers;

use App\ListingAmenities;
use Illuminate\Http\Request;
use Auth;
class ListingAmenitiesController extends Controller
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
            "listing_id" => "required",
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
     * creating multiple  new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function bulk_update(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "listing_id" => "required",
            "amenities" => "required"
        ]);
        if(!empty($data['amenities']))
        {
            $saved_array = [];
            foreach ($data['amenities'] as $key) {
                $new_data = [
                    'listing_id' => $data['listing_id'],
                    'amenity_id' => $key
                ];
                if(!self::check_if_exist($new_data))
                {
                    $saved = self::store($new_data);
                }
                array_push($saved_array, $new_data);
            }
            //return $saved_array;
        }
        return response()->json(['status' => 'ok', 'data'=> $saved_array, 'msg' => 'Data added successfully']); 
    }

    /**
     * check if resource already exist
     *
     * @param  \App\ListingAmenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public static function check_if_exist($data)
    {
        try {
            if(ListingAmenities::where('listing_id', $data['listing_id'])->where('amenity_id', $data['amenity_id'])->exists())
            {
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
            $store = new ListingAmenities;
            $store->listing_id = $data['listing_id'];
            $store->amenity_id = $data['amenity_id'];
            $store->save();
            activity()
               ->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('listing amenity created');
            return $store; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListingAmenities  $listingAmenities
     * @return \Illuminate\Http\Response
     */
    public function show(int $listing_id)
    {
        try {
            $user_amenities = ListingAmenities::where('listing_id', $listing_id)->get();
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
     * @param  \App\ListingAmenities  $listingAmenities
     * @return \Illuminate\Http\Response
     */
    public function edit(ListingAmenities $listingAmenities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListingAmenities  $listingAmenities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListingAmenities $listingAmenities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListingAmenities  $listingAmenities
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListingAmenities $resource_id)
    {
        try {
            if($amenity = ListingAmenities::where('id', $resource_id)->first()){
                ListingAmenities::destroy($resource_id);
                activity()
                   ->causedBy(Auth::user()->id)
                   ->performedOn($amenity)
                   ->withProperties(['id' => $amenity->id])
                   ->log('listing amenity deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
}
