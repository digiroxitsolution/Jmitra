<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class OtherExpenseMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'other_expense_master';
    protected $fillable = [
        'id',
        'other_expense',       
        'created_at',
        'updated_at',
    ];

    public function MonthlyExpense()
    {
        return $this->hasMany(MonthlyExpense::class, 'other_expense_master_id', 'id');
    }
}
