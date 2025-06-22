<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class CompanyMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'company_master';
    protected $fillable = [
        'id',
        'company_name',
        'location',
        'address',
        'created_at',
        'updated_at',
    ];
}
