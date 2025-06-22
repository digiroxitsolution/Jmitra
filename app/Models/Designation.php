<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PolicySettings;
class Designation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        // 'description',
    ];

     // Relationship with PolicySetting
    public function policySettings()
    {
        return $this->hasMany(PolicySettings::class);
    }
}
