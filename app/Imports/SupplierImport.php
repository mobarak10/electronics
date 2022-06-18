<?php

namespace App\Imports;

use App\Models\Party;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Party([
            'code' => 'PRT' . str_pad(Party::withTrashed()->max('id') + 1, 8, '0', STR_PAD_LEFT),
            'name' => $row['name'],
            'description' => $row['description'],
            'phone' => $row['phone'],
            'balance' => $row['balance'],
            'email' => $row['email'],
            'credit_limit' => $row['credit_limit'],
            'address' => $row['address'],
            'thumbnail' => $row['thumbnail'],
            'active' => $row['active'],
            'business_id' => $row['business_id'],
        ]);
    }
}
