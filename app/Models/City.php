<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class City extends Model
{
    //
    protected $table = 'cities';
    protected $fillable = [
        'state_id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function State()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
