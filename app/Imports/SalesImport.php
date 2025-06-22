<?php

namespace App\Imports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\ToModel;

class SalesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (isset($row['sales_master_id'], $row['state_id'], $row['sales_amount'], $row['date_of_sales'])) {
            return new Sales([
                // 'sales_master_id' => $row['sales_master_id'],
                'state' => $row['state'],
                'sales_amount' => $row['sales_amount'],
                'date_of_sales' => $row['date_of_sales'],
            ]);
        }
        return null; // Skip row if required fields are missing
    }
}
