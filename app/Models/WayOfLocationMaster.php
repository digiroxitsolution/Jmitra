<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class WayOfLocationMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'way_of_location_master';
    protected $fillable = [
        'id',
        'way_of_location',
        
        'created_at',
        'updated_at',
    ];
}
