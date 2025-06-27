<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateReason extends Model
{
     protected $fillable=[
        'reason',
        'updated_by',
        'expense_id',
     ];
     public function showUsers()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
}