<?php

namespace App\Http\Controllers;

use App\Amenities;
use Illuminate\Http\Request;
use DB;
use Auth;

class AmenitiesController extends Controller
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
            "name" => "required"
        ]);
        if(self::check_if_name_exist($data['name']))
        {
            return response()->json(['status'=> 'error', 'msg'=> 'Name already exists']);
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
    public function store($data)
    {
        try {
            $store = new Amenities;
            $store->name = $data['name'];
            $store->icon = $data['icon'] ?? null;
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('amenity created');
            return $store; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $amenities = Amenities::get();
        return response()->json(['status'=>'ok', 'data' => $amenities, 'msg' => 'succesful']);
    }

    /**
     * Display the specified resource by limiting
     * the no. of resources to get.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function paginated_amenities()
    {
        $amenities = Amenities::paginate(10);
        return response()->json(['status'=>'ok', 'data' => $amenities, 'msg' => 'succesful']);
    }

    /**
     * check if resource already exist
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public static function check_if_name_exist($amenity_name)
    {
        try {
            if(Amenities::where('name', $amenity_name)->exists())
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
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            "id" => "required",
            "name" => "required"
        ]);
        $data = $request->all();
        try {
            $amenity = Amenities::find($data['id']);
            if(!empty($data['name']))
            {
                $amenity->name = $data['name'];
            }
            if(!empty($data['icon']))
            {
                $amenity->icon = $data['icon'];
            }
            if(!empty($data['description']))
            {
                $amenity->description = $data['description'];
            }
            $amenity->save();
            activity()
                   ->causedBy(Auth::user())
                   ->performedOn($amenity)
                   ->withProperties(['id' => $amenity->id, 'name' => $amenity->name])
                   ->log('amenity updated');
            return response()->json(['status'=> 'ok', 'data' => $amenity, 'msg' => 'Data updated successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $amenity_id)
    {
        try {
            if(Amenities::where('id',$amenity_id)->exists()){
                $amenity = Amenities::where('id', $amenity_id)->first();
                Amenities::destroy($amenity_id);
                activity()
                   ->causedBy(Auth::user())
                   ->performedOn($amenity)
                   ->withProperties(['id' => $amenity->id, 'name' => $amenity->name])
                   ->log('amenity deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
}
