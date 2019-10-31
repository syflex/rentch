<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingFavorite extends Model
{
    public function listing()
    {
        return $this->belongsTo('App\Listing', 'listing_id', 'id')->with('listing_category')->with('state')->with('local_govt');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function listing_category()
    {
        return $this->belongsTo('App\ListingCategory', 'listing_category_id', 'id')->select(['id', 'name']);
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'id')->select(['id', 'state']);
    }

    public function local_govt()
    {
        return $this->belongsTo('App\LocalGovt', 'local_govt_id', 'id')->select(['id', 'name']);
    }
}
