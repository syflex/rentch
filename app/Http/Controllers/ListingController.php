<?php

namespace App\Http\Controllers;

use App\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\ListingAmenitiesController;

use Auth;

class ListingController extends Controller
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
            "listing_category_id" => "required",
            "listing_type" => "required",
            "state_id" => "required",
            "local_govt_id" => "required",
            "title" => "required",
            //"pricing_type" => "required",
            //"amount" => "required"
        ]);
        $store = self::store($data);
        return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'listing created successfully']);
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
            $store = new Listing;
            $store->user_id = $data['user_id'] ?? Auth::user()->id;
            $store->listing_category_id = $data['listing_category_id'];
            $store->listing_type = $data['listing_type'];
            $store->state_id = $data['state_id'];
            $store->city_id = $data['city_id'];
            $store->local_govt_id = $data['local_govt_id'];
            $store->title = $data['title'];
            $store->address = $data['address'];
            $store->description = $data['description'] ?? null;
            $store->room_policy = $data['room_policy'] ?? null;
            $store->service_option = $data['service_option'] ?? 'no';
            $store->service_description = $data['service_description'] ?? null;
            $store->baths = $data['baths'] ?? null;
            $store->rooms = $data['rooms'] ?? null;
            $store->pricing_type = $data['pricing_type'] ?? "monthly";
            $store->amount = $data['amount'] ?? 0;
            $store->amount = $data['step'] ?? 1;
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('listing category created');
               if(!empty($data['property_amenities'])){
                $amenities = new Request(['listing_id' => $store->id, 'amenities'=> $data['property_amenities']]);
                ListingAmenitiesController::bulk_update($amenities);
               }
               return $store;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function get(Listing $listing)
    {
        try {
            $listing = Listing::with('listing_category')->with('state')->with('local_govt')->with('city')->with('image')->orderBy('created_at', 'desc')->paginate(12);
            return response()->json(['status'=> 'ok', 'data'=> $listing, 'msg'=> '']);
        } catch (Exception $e) {
            
        }
    }

    /**
     * Display the specified resource by user.
     *
     * @param  \App\Listing  $user_id
     * @return \Illuminate\Http\Response
     */
    public function user_listings(int $user_id)
    {
        try {
            $listing = Listing::where('user_id', $user_id)->with('listing_category')->with('state')->with('local_govt')->with('city')->with('image')->orderBy('created_at', 'desc')->paginate(12);
            return response()->json(['status'=> 'ok', 'data'=> $listing, 'msg'=> '']);
        } catch (Exception $e) {
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Listing  $listin_singleg
     * @return \Illuminate\Http\Response
     */
    public function show(int $resource_id)
    {
        try {
            $listing = Listing::where('id', $resource_id)->with('listing_category')->with('state')->with('local_govt')->with('city')->with('images')->with('amenities')->orderBy('created_at', 'desc')->first();
            return response()->json(['status'=> 'ok', 'data'=> $listing, 'msg'=> '']);
        } catch (Exception $e) {
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Listing  $listin_singleg
     * @return \Illuminate\Http\Response
     */
    public function paginated(int $pagination)
    {
        try {
            $listing = Listing::with('listing_category')->with('state')->with('local_govt')->with('city')->with('image')->with('amenities')->orderBy('created_at', 'desc')->take($pagination)->get();
            return response()->json(['status'=> 'ok', 'data'=> $listing, 'msg'=> '']);
        } catch (Exception $e) {
            
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function edit(Listing $listing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "id" => "required",
        ]);

        try {
            $store = Listing::where('id', $data['id'])->first();
            #$store->user_id = $data['user_id'] ?? Auth::user()->id;
            if(!empty($data['listing_category_id']))
            {
                $store->listing_category_id = $data['listing_category_id'];
            }
            if(!empty($data['listing_type']))
            {
                $store->listing_type = $data['listing_type'];
            }
            if(!empty($data['state_id']))
            {
                $store->state_id = $data['state_id'];
            }
            if(!empty($data['local_govt_id']))
            {
                $store->local_govt_id = $data['local_govt_id'];
            }
            if(!empty($data['city_id']))
            {
                $store->city_id = $data['city_id'];
            }
            if(!empty($data['title']))
            {
                $store->title = $data['title'];
            }
            if(!empty($data['address']))
            {
                $store->address = $data['address'];
            }
            if(!empty($data['description']))
            {
                $store->description = $data['description'];
            }
            if(!empty($data['room_policy']))
            {
                $store->room_policy = $data['room_policy'];
            }
            if(!empty($data['service_option']))
            {
                $store->service_option = $data['service_option'];
            }
            if(!empty($data['service_description']))
            {
                $store->service_description = $data['service_description'];
            }
            if(!empty($data['baths']))
            {
                $store->baths = $data['baths'];
            }
            if(!empty($data['rooms']))
            {
                $store->rooms = $data['rooms'];
            }
            if(!empty($data['pricing_type']))
            {
                $store->pricing_type = $data['pricing_type'];
            }
            if(!empty($data['baths']))
            {
                $store->amount = $data['amount'];
            }
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('listing category created');
            return response()->json(['status'=> 'ok', 'data'=> $store, 'msg'=> 'listing updated successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function delete(int $resource_id)
    {
        try {
            $delete = Listing::where('id', $resource_id)->delete();
            // activity()
            //    ->causedBy(Auth::user()->id)
            //    ->performedOn($delete)
            //    ->withProperties(['id' => $delete->id])
            //    ->log('listing category created');
            return response()->json(['status'=> 'ok', 'msg'=> 'Data deleted successfully']);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data = $request->all();
        /*$validatedData = $request->validate([
            "user_id" => "required",
            "title" => "required",
        ]);*/
        try {
            $query = Listing::where('featured', 0);

            if((!empty($data['from'])) && (!empty($data['to'])))
            {
                $query->whereDate('created_at', '>=', date($data['from']))
                      ->whereDate('created_at', '<=', date($data['to']));
            } elseif(!empty($data['from']) && empty($data['to']))
            {
                 $query->whereDate('created_at', date($data['from']));
            } 
            if(!empty($data['state_id']))
            {
                $query->where('state_id', $data['state_id']);
            }
            if(!empty($data['city_id']))
            {
                $query->where('city_id', $data['city_id']);
            }
            if(!empty($data['listing_category_id']))
            {
                $query->where('listing_category_id', $data['listing_category_id']);
            }
            if(!empty($data['listing_type']))
            {
                $query->where('listing_type', $data['listing_type']);
            }
             /*if(!empty($data['staff_id'])) 
              {
                $query->where('staff_id', $data['staff_id']);
              } 
              if(!empty($data['period'])) 
              {
                $query->where('period', $data['period']);
              } 
              if (!empty($data['type'])) 
              {
                $query->where('type', $data['type']);
              } 
              if (!empty($data['member_id'])) 
              {
                $query->where('member_id', $data['member_id']);
              } 
              if (!empty($data['plan_id']))
              {
                $query->where('plan_id', $data['plan_id']);
              } 
              if (!empty($data['branch_id']))
              {
                $query->where('branch_id', $data['branch_id']);
              } 
              if (!empty($data['transaction_id'])) 
              {
                $query->where('transaction_id', $data['transaction_id']);
              }*/

              if (!empty($data['status'])) 
              {
                $query->where('status', '=', $data['status']);
              }

            $query->orderBy('created_at','DESC')->with('listing_category')->with('state')->with('local_govt')->with('city');
            if($query->count() > 0 )
            {
                return response()->json(['status'=> 'ok', 'data'=> $query->paginate(9), 'count' => $query->count() , 'msg'=> 'search result']);
            }
            return response()->json(['status'=> 'ok', 'data'=> $query->paginate(9), 'count' => $query->count() , 'msg'=> 'search result']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
