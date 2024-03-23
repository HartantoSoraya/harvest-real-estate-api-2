<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_type_pivot');
    }
}
