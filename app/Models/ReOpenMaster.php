<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ReOpenMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 're_open_master';
    protected $fillable = [
        'id',
        'reason_of_re_open',
        
        'created_at',
        'updated_at',
    ];
}
