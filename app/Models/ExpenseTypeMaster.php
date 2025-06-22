<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ExpenseTypeMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'expense_type_master';
    protected $fillable = [
        'id',
        'expense_type',       
        'created_at',
        'updated_at',
    ];

    public function MonthlyExpense()
    {
        return $this->hasMany(MonthlyExpense::class, 'expense_type_master_id', 'id');
    }
}
