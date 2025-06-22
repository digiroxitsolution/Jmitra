<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Hod extends Model
{

    use HasFactory, Notifiable, HasRoles;
    protected $table = 'hods';
    protected $fillable = ['id', 'name'];


    public function monthlyExpenses()
    {
        return $this->hasMany(MonthlyExpense::class, 'hod_id', 'id');
    }
}
