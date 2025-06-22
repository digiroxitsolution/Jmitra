<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PolicySettings;
class PolicyGuideliness extends Model
{
    use HasFactory;
    protected $table = 'policy_guidelines';

    protected $fillable = [
        'id',
        'policy_setting_id',
        'file_name',
        'policy_description',
        'uploaded_file',        
        'created_at',
        'updated_at',

    ];

    

    // Relationship with FoodItem
    public function PolicySettings()
    {
        return $this->belongsTo(PolicySettings::class, 'policy_setting_id',);
    }
}
