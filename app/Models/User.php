<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

use App\Models\UserDetails;
use App\Models\MonthlyExpense;
use App\Models\UserExpenseOtherRecords;
use App\Models\Attendance;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function showUsers()
    {
        $totalUsers = User::count();
        return view('your-view-name', compact('totalUsers'));
    }

    // Define the relationship with the UserDetail model
    public function userDetail()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    public function monthlyExpenses()
    {
        return $this->hasMany(MonthlyExpense::class, 'user_id', 'id');
    }

    public function UserExpenseOtherRecords()
    {
        return $this->hasMany(UserExpenseOtherRecords::class, 'user_id', 'id');
    }

    public function Attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    public function approvedExpenses()
    {
        return $this->hasMany(MonthlyExpense::class, 'approved_by', 'id');
    }
}
