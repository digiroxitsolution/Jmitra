<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Attendance extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'attendance';
    protected $fillable = [
         'user_id',
         'state_id',
         'customer_name',
         'customer_type',
         'zone',
         'check_in',
         'check_in_address',
         'check_in_remarks',
         'check_out_remarks',
         'check_out',
         'purpose',
         'description',
         'joint_purpose_details',
         'created_at',
         'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }


}
