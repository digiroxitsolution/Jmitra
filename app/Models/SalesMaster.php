<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sales;


class SalesMaster extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'sales_master';
    protected $fillable = [
        'id',
        'file_name',
        'date_of_upload',        
        'created_at',
        'updated_at',
    ];


    public function sales()
    {
        return $this->hasMany(Sales::class, 'sales_master_id', 'id');
    }
}
