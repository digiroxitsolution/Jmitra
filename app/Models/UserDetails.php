<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DivisonMaster;

class UserDetails extends Model
{
    // Define the table name explicitly
    protected $table = 'user_details';

    // Define the fillable fields
    protected $fillable = [
        'id',
        'user_id',
        'employee_id',
        'designation_id',
        'company_master_id',
        'location_master_id',
        'hod_id',
        'policy_setting_id',
        'city_id',
        'state_id',
        'divison_master_id',
        'created_at',
        'updated_at',
    ];

    // Boot method for generating employee_id automatically
    // protected static function booted()
    // {
    //     static::creating(function ($userDetail) {
    //         // Generate employee_id based on your custom logic
    //         $userDetail->employee_id = 'EMP-' . str_pad(UserDetails::count() + 1, 5, '0', STR_PAD_LEFT);
    //     });
    // }

    protected static function booted()
    {
        static::creating(function ($userDetail) {
            // Generate employee_id before creating the record
            if (empty($userDetail->employee_id)) {
                $userDetail->employee_id = 'EMP-' . str_pad(UserDetails::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function DivisonMaster()
    {
        return $this->belongsTo(DivisonMaster::class, 'divison_master_id');
    }

    public function companyMaster()
    {
        return $this->belongsTo(CompanyMaster::class, 'company_master_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function locationMaster()
    {
        return $this->belongsTo(LocationMaster::class, 'location_master_id');
    }

    public function hod()
    {
        return $this->belongsTo(Hod::class, 'hod_id');
    }

    public function policySetting()
    {
        return $this->belongsTo(PolicySettings::class, 'policy_setting_id');
    }

}
