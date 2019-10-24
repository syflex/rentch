<?php

namespace App\Http\Controllers;

use App\ListingType;
use Illuminate\Http\Request;

class ListingTypeController extends Controller
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
    public function create()
    {
        $data = $request->all();
        $store = self::store($data);
        return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'listing category created successfully']);
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
            if($data['id'])
            {
                $store = ListingType::find($data['id']);
            }else{
                $store = new ListingType;
            }
            $store->name = $data['name'];
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               ->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('listing type created');
               return $store;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListingType  $listingType
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $listing_type = ListingType::all();
            return response()->json(['status'=> 'ok', 'data'=> $listing_type, 'msg'=> 'data loaded successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListingType  $listingType
     * @return \Illuminate\Http\Response
     */
    public function edit(ListingType $listingType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListingType  $listingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListingType $listingType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListingType  $listingType
     * @return \Illuminate\Http\Response
     */
    public static function destroy(int $resource_id)
    {
        try {
            $delete = ListingType::where('id', $resource_id)->delete();
            return response()->json(['status'=> 'ok', 'msg'=> 'Data deleted successfully']);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
