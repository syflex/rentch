<?php

namespace App\Http\Controllers;

use App\ListingFavorite;
use Illuminate\Http\Request;
use Auth;
class ListingFavoriteController extends Controller
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
            "listing_id" => "required"
        ]);
        $store = self::store($data);
        return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'listing favorite created successfully']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        $store = new ListingFavorite;
        $store->listing_id = $data['listing_id'];
        $store->user_id = $data['user_id'];
        $store->save();
        activity()
           ->causedBy(Auth::user())
           ->performedOn($store)
           ->withProperties(['id' => $store->id])
           ->log('listing favorite created');
        return $store;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListingFavorite  $listingFavorite
     * @return \Illuminate\Http\Response
     */
    public function show(ListingFavorite $listingFavorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListingFavorite  $listingFavorite
     * @return \Illuminate\Http\Response
     */
    public function user_favorites(int $user_id)
    {
        $data = ListingFavorite::where('user_id', $user_id)->with('listing')->paginate(9);
        return response()->json(['status'=> 'ok', 'data'=> $data, 'msg'=> 'data loaded']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListingFavorite  $listingFavorite
     * @return \Illuminate\Http\Response
     */
    public function listing_favorites(int $listing_id)
    {
        $data = ListingFavorite::where('listing_id', $listing_id)->with('listing')->with('user')->paginate(9);
        return response()->json(['status'=> 'ok', 'data'=> $data, 'msg'=> 'data loaded']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListingFavorite  $listingFavorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "user_id" => "required",
            "id" => "required",
        ]);
        $fav = ListingFavorite::where('id', $data['id'])->first(); 
        $delete = ListingFavorite::where('id', $data['id'])->delete(); 
        activity()
           ->causedBy(Auth::user())
           ->performedOn($fav)
           ->withProperties(['id' => $fav->id])
           ->log('listing favorite deleted');
        return response()->json(['status'=> 'ok', 'msg'=> 'listing favorite deleted successfully']);

    }
}
