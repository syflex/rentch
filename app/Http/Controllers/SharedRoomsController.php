<?php

namespace App\Http\Controllers;

use App\SharedRooms;
use Illuminate\Http\Request;

class SharedRoomsController extends Controller
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
            "listing_id" => "required"
            "title" => "required"
            "pricing_type" => "required"
            "amount" => "required"
        ]);
        $store = self::store($data);
        return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'Data created successfully']);
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
            $store = new SharedRooms;
            $store->listing_id = $data['listing_id'];
            $store->title = $data['title'];
            $store->pricing_type = $data['pricing_type'];
            $store->amount = $data['amount'];
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               ->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('shared rooms created');
            return $store;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SharedRooms  $sharedRooms
     * @return \Illuminate\Http\Response
     */
    public function show(SharedRooms $sharedRooms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SharedRooms  $sharedRooms
     * @return \Illuminate\Http\Response
     */
    public function edit(SharedRooms $sharedRooms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SharedRooms  $sharedRooms
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request, $resource_id)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "listing_id" => "required"
            "title" => "required"
            "pricing_type" => "required"
            "amount" => "required"
        ]);
        try {
            $store = SharedRooms::find($resource_id);
            $store->listing_id = $data['listing_id'];
            $store->title = $data['title'];
            $store->pricing_type = $data['pricing_type'];
            $store->amount = $data['amount'];
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               ->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('shared rooms created');
            return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'Data updated successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SharedRooms  $sharedRooms
     * @return \Illuminate\Http\Response
     */
    public static function destroy(int $resource_id)
    {
        try {
            $delete = SharedRooms::where('id', $resource_id)->delete();
            return response()->json(['status'=> 'ok', 'msg'=> 'Data deleted successfully']);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
