<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sales;

class State extends Model
{
    //
    protected $table = 'states';
    protected $fillable = [
        'id',
        'name',
        'short',
        'created_at',
        'updated_at',
    ];
    
    // Relationship with IncidentReport
    public function City()
    {
        return $this->hasMany(City::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function Attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

}
