<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Designation;
use App\Models\PolicyGuideliness;

class PolicySettings extends Model
{
    use HasFactory;
    protected $table = 'policy_settings';

    protected $fillable = [
        'id',
        'policy_id',
        'policy_name',
        'designation_id',
        'location_da',
        'ex_location_da',
        'outstation_da',
        'intercity_travel_ex_location',
        'intercity_travel_outstation',
        'two_wheelers_charges',
        'four_wheelers_charges',        
        'expense_submision_date',
        'approved_submission_date',
        'effective_date',
        'other',
        'created_at',
        'updated_at',

    ];

    

    // Relationship with FoodItem
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id',);
    }

     // Relationship with PolicySetting
    public function PolicyGuideliness()
    {
        return $this->hasMany(PolicyGuideliness::class);
    }
}
