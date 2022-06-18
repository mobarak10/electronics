<?php
namespace App\Imports;
use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StocksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Stock([
            'product_id'  => $row['product_id'],
            'warehouse_id' => $row['warehouse_id'],
            'quantity'    => $row['quantity'],
            'average_purchase_price'    => $row['average_purchase_price'],
        ]);
    }
}
