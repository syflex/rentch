<?php

namespace App\Http\Controllers;

use App\LocalGovt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocalGovtController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($data)
    {
        $store = new LocalGovt;
        $store->state_id = $data['state_id'];
        $store->name = $data['name'];
        $store->save();
        return $store;
        
    }

    /**
     * check if specified resource exist.
     *
     * @param  \App\loca$local_govt  $local_govt
     * @return \Illuminate\Http\Response
     */
    public static function local_govt_exist(string $local_govt)
    {
        if(LocalGovt::where('name', $local_govt)->exists())
        {
            return 1;
        }
        return 0;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LocalGovt  $localGovt
     * @return \Illuminate\Http\Response
     */
    public function show(LocalGovt $localGovt)
    {
        $locals = LocalGovt::get();
        return response()->json(['status'=> 'ok', 'data'=>$locals]);
    }

    /**
     * Show specified resource by state.
     *
     * @param  \App\State  $state_id
     * @return \Illuminate\Http\Response
     */
    public function state_locals(int $state_id)
    {
        $state_locals = LocalGovt::where('state_id', $state_id)->get();
        return response()->json(['status' => 'ok', 'data' => $state_locals]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LocalGovt  $localGovt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LocalGovt $localGovt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LocalGovt  $localGovt
     * @return \Illuminate\Http\Response
     */
    public function destroy(LocalGovt $localGovt)
    {
        //
    }
}
