<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Listing extends Model
{
    use SoftDeletes;

    public function listing_category()
    {
        return $this->belongsTo('App\ListingCategory', 'listing_category_id', 'id')->select(['id', 'name']);
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'id')->select(['id', 'state']);
    }
    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id')->select(['id', 'name']);
    }

    public function local_govt()
    {
        return $this->belongsTo('App\LocalGovt', 'local_govt_id', 'id')->select(['id', 'name']);
    }

    public function amenities()
    {
        return $this->hasMany(ListingAmenities::class, 'listing_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class, 'listing_id', 'id');
    }
    public function image()
    {
        return $this->belongsTo(ListingImage::class, 'id', 'listing_id');
    }

    public function share_rooms()
    {
        
    }
}
