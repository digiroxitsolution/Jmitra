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
use App\Models\ExpenseTypeMaster;

use App\Models\OtherExpenseMaster;
use App\Models\MonthlyExpenseHistory;


class MonthlyExpense extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'monthly_expenses';
    protected $fillable = [
        'id',
        'user_id',
        'expense_id',
        'expense_type_master_id',
        'mode_of_expense_master_id',
        'expense_date',
        'one_way_two_way_multi_location',
        'from',
        'to',
        'departure_time',
        'arrival_time',
        'km_as_per_user',
        'km_as_per_google_map',
        'fare_amount',
        'da_location',
        'da_ex_location',
        'da_outstation',
        'da_total',
        'postage',
        'mobile_internet',
        'print_stationery',
        'other_expense_master_id',
        'other_expenses_amount',
        'pre_approved',
        'approved_date',
        'approved_by',
        'hod_id',
        'upload_of_approvals_documents',
        'status',
        'is_submitted',
        'accept_policy',
        'user_expense_other_records_id',
        'remarks',
        'created_at',
        'updated_at',
    ];


    public function hod()
    {
        return $this->belongsTo(Hod::class, 'hod_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function divison()
    {
        return $this->belongsTo(DivisonMaster::class, 'divison_master_id', 'id');
    }

    public function ModeofExpenseMaster()
    {
        return $this->belongsTo(ModeofExpenseMaster::class, 'mode_of_expense_master_id', 'id');
    }

    public function OtherExpenseMaster()
    {
        return $this->belongsTo(OtherExpenseMaster::class, 'other_expense_master_id', 'id');
    }

    public function ExpenseTypeMaster()
    {
        return $this->belongsTo(ExpenseTypeMaster::class, 'expense_type_master_id', 'id');
    }

    public function monthlyExpenseHistory()
    {
        return $this->hasMany(MonthlyExpenseHistory::class, 'monthly_expense_id', 'id');
    }

    public function monthlyExpenseHistory2()
    {
        return $this->hasMany(MonthlyExpenseHistory2::class, 'monthly_expense_id', 'id');
    }

    public function UserExpenseOtherRecords()
    {
        return $this->belongsTo(UserExpenseOtherRecords::class, 'user_expense_other_records_id', 'id');
    }

    public function userDetails()
    {
        return $this->belongsTo(UserDetails::class, 'user_id', 'user_id');
    }

    public function state()
    {
        return $this->hasOneThrough(
            State::class,
            UserDetails::class,
            'user_id', // Foreign key on `user_details` table
            'id',      // Foreign key on `states` table
            'user_id', // Local key on `monthly_expenses` table
            'state_id' // Local key on `user_details` table
        );
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    
}
