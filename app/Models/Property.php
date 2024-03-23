<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'title',
        'description',
        'loc_city',
        'loc_latitude',
        'loc_longitude',
        'loc_address',
        'loc_state',
        'loc_zip',
        'loc_country',
        'price',
        'agent_id',
        'is_featured',
        'is_active',
        'is_sold',
        'is_rented',
        'offer_type',
        'slug',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'integer',
        'is_active' => 'boolean',
        'is_sold' => 'boolean',
        'is_rented' => 'boolean',
    ];

    public function floorPlans()
    {
        return $this->hasMany(FloorPlan::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(PropertyAmenity::class, 'property_amenity_pivot');
    }

    public function categories()
    {
        return $this->belongsToMany(PropertyCategory::class, 'property_category_pivot');
    }

    public function types()
    {
        return $this->belongsToMany(PropertyType::class, 'property_type_pivot');
    }
}
