<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'code',
        'name',
        'description',
        'specialization',
        'email',
        'phone_number',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'avatar',
        'slug',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
