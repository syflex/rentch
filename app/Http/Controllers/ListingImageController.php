<?php

namespace App\Http\Controllers;

use App\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Auth;
class ListingImageController extends Controller
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
            "listing_id" => "required",
            "title" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,svg|max:2048"
        ]);
        $data = $request->all();
        if ($request->hasFile('image')) {
                $path = $request->file('image')->store('listings');
                $array = [
                    'name' => $path,
                    'listing_id' => $data['listing_id'],
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null
                ];
                $store = self::store($array);
                return response()->json(['status' => 'ok','data'=> $store, 'msg'=>'Image Upload successfully']);
            }
            return response()->json(['status' => 'error']);
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
            $store = new ListingImage;
            $store->listing_id = $data['listing_id'];
            $store->name = $data['name'];
            $store->title = $data['title'] ?? null;
            $store->description = $data['description'] ?? null;
            $store->save();
            activity()
               //->causedBy(Auth::user()->id)
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('Listing Image uploaded');
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
            "listing_id" => "required",
            "images" => "required"
        ]);
        if(!empty($data['images']))
        {
            $saved_array = [];
            foreach ($data['images'] as $key) {
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
     * Display the specified resource.
     *
     * @param  \App\ListingImage  $listingImage
     * @return \Illuminate\Http\Response
     */
    public function show(ListingImage $listingImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListingImage  $listingImage
     * @return \Illuminate\Http\Response
     */
    public function edit(ListingImage $listingImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListingImage  $listingImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListingImage $listingImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListingImage  $resource_id
     * @return \Illuminate\Http\Response
     */
    public static function destroy(int $resource_id)
    {
        try {
            $image_data = ListingImage::where('id', $resource_id)->first();
            self::delete_image($image_data->name);
            $delete = ListingImage::where('id', $resource_id)->delete();

            // activity()
            //    ->causedBy(Auth::user()->id)
            //    ->performedOn($delete)
            //    ->withProperties(['id' => $delete->id])
            //    ->log('listing image deleted');
            return response()->json(['status'=> 'ok', 'msg'=> 'Data deleted successfully']);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
    /**
     * delete user image/avata
     *
     * @method upload_user_image
     * @param  \App\User  $request
     * @return \Illuminate\Http\Response
     */
    public static function delete_image($image_path)
    {
         Storage::delete($image_path);
    }
}
