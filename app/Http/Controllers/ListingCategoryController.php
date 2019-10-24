<?php

namespace App\Http\Controllers;

use App\ListingCategory;
use Illuminate\Http\Request;
use Auth;
class ListingCategoryController extends Controller
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
        $validatedData = $request->validate([
            "name" => "required"
        ]);
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
    public static function store($data, $resource_id = 0)
    {
        try {
            $store = new ListingCategory;
            if($resource_id > 0)
            {
                $store = ListingCategory::find($resource_id);
            }
            $store->name = $data['name'];
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['name' => $store->name])
               ->log('listing category created/updated');
               return $store;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListingCategory  $listingCategory
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $listing_category = ListingCategory::all();
            return response()->json(['status'=> 'ok', 'data'=> $listing_category, 'msg'=> 'data loaded successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListingCategory  $listingCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ListingCategory $listingCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListingCategory  $listingCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $resource_id)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "name" => "required"
        ]);
        $store = self::store($data, $resource_id);
        return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'listing category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListingCategory  $listingCategory
     * @return \Illuminate\Http\Response
     */
    public function delete(int $resource_id)
    {
        try {
            if($listing_cat = ListingCategory::where('id', $resource_id)->first())
            {
                $delete = ListingCategory::where('id', $resource_id)->delete();
                activity()
                   ->causedBy(Auth::user())
                   ->performedOn($listing_cat)
                   ->withProperties(['name' => $resource_id])
                   ->log('listing category deleted');
            return response()->json(['status'=> 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status'=> 'error', 'msg'=> 'Data doesnt exist']);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
