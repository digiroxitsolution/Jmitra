<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;
class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $fillable = [
        'id',
        'sales_master_id',
        'state_id',
        'sales_amount',
        'date_of_sales',
        'created_at',
        'updated_at'
    ];
    
    // Define Relationship with SalesMaster
    public function salesMaster()
    {
        return $this->belongsTo(SalesMaster::class, 'sales_master_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
