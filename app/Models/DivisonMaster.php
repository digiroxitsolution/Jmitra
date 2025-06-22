<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserDetails;
class DivisonMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'divison_master';
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];


    public function userDetail()
    {
        return $this->hasMany(UserDetails::class, 'divison_master_id', 'id');
    }
}
