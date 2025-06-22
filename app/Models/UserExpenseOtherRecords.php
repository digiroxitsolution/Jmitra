<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;
use App\Models\Hod;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;


class UserExpenseOtherRecords extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'user_expense_other_records';
    protected $fillable = [
        'id',
        'user_id',
        'hod_id',
        'expense_date',
        'expense_id',        
        'is_submitted',
        'date_of_submission',       
        'advance_taken',
        'remark_of_advance_taken',
        'verified_by',
        'approval_deadline',
        'verified_time',
        'is_verified',
        'is_approved',
        'approved_by',
        'approved_time',
        'accept_policy',
        'days_elapsed',
        'verification_days_elapsed',
        'approval_days_elapsed',
        'justification',
        'status',
        'sla_status',
        'sla_status_of_submission',
        'sla_status_of_approval',
        're_open_master_id',
        'rejection_master_id',        
        'remarks',
        'created_at',
        'updated_at',




    ];

    public function MonthlyExpense()
    {
        return $this->hasMany(MonthlyExpense::class, 'user_expense_other_records_id', 'id');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

     public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relationship to fetch the user who approved the expense.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hod()
    {
        return $this->belongsTo(Hod::class, 'hod_id', 'id');
    }

    

    

    
}
