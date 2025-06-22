<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\MonthlyExpense;

class ModeofExpenseMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'mode_of_expense_master';
    protected $fillable = [
        'id',
        'mode_expense',       
        'created_at',
        'updated_at',
    ];

    public function MonthlyExpense()
    {
        return $this->hasMany(MonthlyExpense::class, 'mode_of_expense_master_id', 'id');
    }
}
