<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class LocationMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'location_master';
    protected $fillable = [
        'id',
        'city_id',
        'state_id',
        'working_location',
        'created_at',
        'updated_at',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
